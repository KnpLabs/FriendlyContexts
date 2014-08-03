<?php

namespace Knp\FriendlyContexts\Alice\Loader;

use Knp\FriendlyContexts\Alice\ProviderResolver;
use Nelmio\Alice\Loader\Yaml as BaseLoader;

class Yaml extends BaseLoader
{
    private $cache = [];

    public function __construct($locale, ProviderResolver $providers)
    {
        parent::__construct($locale, $providers->all());
    }

    public function getCache()
    {
        return $this->cache;
    }

    public function clearCache()
    {
        $this->cache = [];
    }

    protected function createInstance($class, $name, array &$data)
    {
        $instance = parent::createInstance($class, $name, $data);
        $this->cache[] = [ $data, $instance ];

        return $instance;
    }
}
