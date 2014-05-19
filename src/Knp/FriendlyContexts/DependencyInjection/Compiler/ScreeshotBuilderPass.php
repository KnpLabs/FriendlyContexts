<?php

namespace Knp\FriendlyContexts\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ScreeshotBuilderPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $managerDefinition = $container->getDefinition('friendly.screeshot.registry');
        foreach (array_keys($container->findTaggedServiceIds('friendly.screeshot.builder')) as $id) {
            $managerDefinition->addMethodCall('addBuilder', [ new Reference($id) ]);
        }
    }
}
