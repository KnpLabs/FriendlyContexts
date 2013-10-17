<?php

namespace Knp\FriendlyContexts;

use Behat\Behat\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Behat\Gherkin\Loader\YamlFileLoader;

class Extension implements ExtensionInterface
{
    public function load(array $config, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__));
        $loader->load('core.yml');
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
                            ->arrayNode('SwiftMailer')
                                ->children()
                                    ->scalarNode('enable')->defaultFalse()->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    public function getCompilerPasses()
    {
        return [];
    }
}
