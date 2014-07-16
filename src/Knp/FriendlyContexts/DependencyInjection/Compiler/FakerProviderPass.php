<?php

namespace Knp\FriendlyContexts\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class FakerProviderPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        foreach (array_keys($container->findTaggedServiceIds('friendly.faker.provider')) as $id) {
            $fakerDefinition = $container->getDefinition($id);
            $fakerDefinition->addMethodCall('initialise');
        }
    }
}
