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
        $container->setParameter('friendly.parameters', $config);
        $parameters = [];
        $this->buildParameters('friendly', $parameters, $config);

        foreach ($parameters as $key => $value) {
            $container->setParameter($key, $value);
        }

        if (empty($config['api']['base_url'])) {
            if (!$container->hasParameter('mink.base_url')) {
                throw new \RuntimeException(sprintf(
                    'You must precised a api.base_url !'
                ));
            }

            $config['api']['base_url'];
        }

        $container->setParameter('api.base_url', $config['api']['base_url']);

        $loader->load('core.yml');
        $loader->load('fakers.yml');
        $loader->load('guessers.yml');

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
                ->scalarNode('smartTag')
                    ->defaultValue('smartStep')
                ->end()
                ->arrayNode('api')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('base_url')
                            ->defaultValue('')
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

    protected function buildParameters($name, &$parameters, $config)
    {
        foreach ($config as $key => $element) {
            if (is_array($element) && $this->arrayHasStringKeys($element)) {
                $this->buildParameters(sprintf('%s.%s', $name, $key), $parameters, $element);
            }
            $parameters[sprintf('%s.%s', $name, $key)] = $element;
        }
    }

    protected function arrayHasStringKeys(array $array)
    {
        foreach ($array as $key => $value) {
            if (is_string($key)) {

                return true;
            }
        }

        return false;
    }
}
