<?php

namespace Knp\FriendlyContexts\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class FakerProviderPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $managerDefinition = $container->getDefinition('friendly.faker.guesser');

        foreach (array_keys($container->findTaggedServiceIds('friendly.faker.provider')) as $id) {
            $managerDefinition->addMethodCall('addProvider', [ new Reference($id) ]);
        }
    }
}
