<?php

namespace Knp\FriendlyContexts;

use Behat\Testwork\ServiceContainer\Extension as ExtensionInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Knp\FriendlyContexts\DependencyInjection\Compiler;
use Behat\Testwork\ServiceContainer\ExtensionManager;

class Extension implements ExtensionInterface
{
    public function initialize(ExtensionManager $extensionManager)
    {

    }

    public function load(ContainerBuilder $container, array $config)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/services'));
        $loader->load('core.yml');
        $loader->load('fakers.yml');
        $loader->load('guessers.yml');

        $container->setParameter('friendly.parameters', $config);
    }

    public function configure(ArrayNodeDefinition $builder)
    {
        $builder
            ->children()
                ->arrayNode('symfony_kernel')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('bootstrap')
                            ->defaultValue('app/autoload.php')
                        ->end()
                        ->scalarNode('path')
                            ->defaultValue('app/AppKernel.php')
                        ->end()
                        ->scalarNode('class')
                            ->defaultValue('AppKernel')
                        ->end()
                        ->scalarNode('env')
                            ->defaultValue('test')
                        ->end()
                        ->booleanNode('debug')
                            ->defaultTrue()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('Contexts')
                    ->isRequired()
                        ->children()
                            ->arrayNode('Smart')
                            ->end()
                            ->arrayNode('Entity')
                                ->children()
                                    ->arrayNode('namespaces')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->variableNode('Tags')
            ->end()
        ;
    }

    public function process(ContainerBuilder $container)
    {
        return [
           new Compiler\FormatGuesserPass,
           new Compiler\FakerProviderPass,
           new Compiler\KernelPass,
        ];
    }

    public function getConfigKey()
    {
        return 'friendly';
    }
}
