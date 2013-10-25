<?php

namespace Knp\FriendlyContexts\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class FormatGuesserPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $managerDefinition = $container->getDefinition('friendly.guesser.manager');

        foreach ($container->findTaggedServiceIds('friendly.format.guesser') as $id => $attributes) {
            $managerDefinition->addMethodCall('addGuesser', [ new Reference($id) ]);
        }
    }
}
