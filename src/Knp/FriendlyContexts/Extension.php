<?php

namespace Knp\FriendlyContexts;

use Behat\Behat\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Knp\FriendlyContexts\DependencyInjection\Compiler;


class Extension implements ExtensionInterface
{
    public function load(array $config, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__));
        $loader->load('core.yml');

        $container->setParameter('friendly.parameters', $config);
    }

    public function getConfig(ArrayNodeDefinition $builder)
    {
        $builder
            ->children()
                ->arrayNode('Contexts')
                    ->isRequired()
                        ->children()
                            ->arrayNode('Entity')
                                ->children()
                                    ->scalarNode('enable')->defaultFalse()->end()
                                    ->arrayNode('namespaces')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->variableNode('Tags')
            ->end()
        ;
    }

    public function getCompilerPasses()
    {
        return [
           new Compiler\FormatGuesserPass,
        ];
    }
}
