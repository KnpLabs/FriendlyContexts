<?php

namespace Knp\FriendlyExtension\DependencyInjection\Compiler;

use Faker\Provider\Base;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class FakerProviderRegistrationPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $ids = array_keys($container->findTaggedServiceIds('friendly.faker.provider'));
        $generator = $container->get('friendly.faker.generator');

        foreach ($generator->getProviders() as $provider) {
            $identifier = sprintf('friendly.faker.provider.%s', strtolower((new \ReflectionClass($provider))->getShortName()));
            $legacy     = sprintf('%s.legacy', $identifier);
            $container->setDefinition($legacy, $this->buildProviderDefinition($provider));

            $definition = $this->buildBaseProviderDefinition();

            foreach ($ids as $id) {
                $child = $container->get($id);

                if ($child->supportsParent($provider)) {
                    $definition = $container->getDefinition($id);
                }
            }

            $definition->addMethodCall('setParent', [ new Reference($legacy) ]);
            $definition->addTag('friendly.faker.provider');

            $container->setDefinition($identifier, $definition);
        }
    }

    private function buildProviderDefinition(Base $provider)
    {
        return (new Definition())
            ->setClass(get_class($provider))
            ->addArgument(new Reference('friendly.faker.generator'))
        ;
    }

    private function buildBaseProviderDefinition()
    {
        return (new Definition())
            ->setClass('Knp\FriendlyExtension\Faker\Provider\Base')
        ;
    }
}
