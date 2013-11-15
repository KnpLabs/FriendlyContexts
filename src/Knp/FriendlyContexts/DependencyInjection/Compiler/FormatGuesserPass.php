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
        foreach (array_keys($container->findTaggedServiceIds('friendly.format.guesser')) as $id) {
            $managerDefinition->addMethodCall('addGuesser', [ new Reference($id) ]);
            $guesserDefinition = $container->getDefinition($id);
            foreach (array_keys($container->findTaggedServiceIds(sprintf('%s.faker', $id))) as $id2) {
                $guesserDefinition->addMethodCall('addFaker', [ new Reference($id2) ]);
            }
        }
    }
}
