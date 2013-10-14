<?php

namespace Knp\FriendlyContexts;

use Behat\Behat\Extension\ExtensionInterface;

class Extension implements ExtensionInterface
{
    public function load(array $config, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__));
        $loader->load('core.yml');
    }
}
