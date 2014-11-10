<?php

namespace Knp\FriendlyExtension\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SetArgumentToNullPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        foreach ($container->findTaggedServiceIds('null-when-missing') as $id => $tags) {
            $definition = $container->getDefinition($id);
            $arguments = $definition->getArguments();
            foreach ($tags as $tag) {
                if (!isset($tag['service'])) {
                    continue;
                }

                if ($container->hasDefinition($tag['service'])) {
                    continue;
                }

                if ($container->hasAlias($tag['service'])) {
                    continue;
                }

                foreach ($arguments as $index => $argument) {
                    if ($tag['service'] === (string)$argument) {
                        $arguments[$index] = null;
                    }
                }

                $definition->setArguments($arguments);
            }
        }
    }
}
