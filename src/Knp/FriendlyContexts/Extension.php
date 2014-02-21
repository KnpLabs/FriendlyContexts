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

        $container->addCompilerPass(new Compiler\FormatGuesserPass);
        $container->addCompilerPass(new Compiler\FakerProviderPass);
        $container->addCompilerPass(new Compiler\KernelPass($config));
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
                ->arrayNode('alice')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('fixtures')
                            ->prototype('scalar')->end()
                        ->end()
                        ->arrayNode('dependencies')
                            ->useAttributeAsKey('name')
                            ->prototype('array')
                                ->prototype('scalar')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('page')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('namespace')
                            ->defaultValue('Page')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    public function process(ContainerBuilder $container)
    {
    }

    public function getConfigKey()
    {
        return 'friendly';
    }
}
