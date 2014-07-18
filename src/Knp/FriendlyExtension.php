<?php

namespace Knp;

use Behat\Testwork\ServiceContainer\Extension as ExtensionInterface;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Faker\Factory;
use Knp\FriendlyExtension\DependencyInjection\Compiler\ApiUrlTransitionPass;
use Knp\FriendlyExtension\DependencyInjection\Compiler\ContextHelperRegistrationPass;
use Knp\FriendlyExtension\DependencyInjection\Compiler\FakerGuesserRegistrationPass;
use Knp\FriendlyExtension\DependencyInjection\Compiler\FakerProviderRegistrationPass;
use Knp\FriendlyExtension\DependencyInjection\Compiler\KernelRegistrationPass;
use Knp\FriendlyExtension\DependencyInjection\Compiler\ParameterBuildingPass;
use Knp\FriendlyExtension\DependencyInjection\Compiler\SymfonyServicePass;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class FriendlyExtension implements ExtensionInterface
{
    public function getConfigKey()
    {
        return 'friendly';
    }

    public function initialize(ExtensionManager $extensionManager)
    {

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
                ->arrayNode('api')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('base_url')
                            ->defaultValue('')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('faker')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('locale')
                            ->defaultValue(Factory::DEFAULT_LOCALE)
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('smartTag')
                    ->defaultValue('smartStep')
                ->end()
            ->end()
        ;
    }

    public function load(ContainerBuilder $container, array $config)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/FriendlyExtension/services'));
        $loader->load('context.yml');
        $loader->load('core.yml');
        $loader->load('faker.yml');
        $loader->load('guesser.yml');
        $loader->load('symfony.yml');

        $container->setParameter('friendly.parameters', $config);

        $container->addCompilerPass(new ParameterBuildingPass);
        $container->addCompilerPass(new KernelRegistrationPass);
        $container->addCompilerPass(new FakerProviderRegistrationPass);
        $container->addCompilerPass(new FakerGuesserRegistrationPass);
        $container->addCompilerPass(new ApiUrlTransitionPass);
        $container->addCompilerPass(new ContextHelperRegistrationPass);
        $container->addCompilerPass(new SymfonyServicePass);
    }

    public function process(ContainerBuilder $container)
    {
    }
}
