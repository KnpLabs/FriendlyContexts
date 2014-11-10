<?php

namespace Knp\FriendlyExtension\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ContextHelperRegistrationPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $helpers = array_keys($container->findTaggedServiceIds('friendly.context.helper'));
        $regitry = $container->getDefinition('friendly.context.helper.registry');

        foreach ($helpers as $helper) {
            $regitry->addMethodCall('addHelper', [ new Reference($helper) ]);
        }
    }
}
