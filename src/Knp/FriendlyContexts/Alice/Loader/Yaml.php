<?php

namespace Knp\FriendlyContexts\Alice\Loader;

use Nelmio\Alice\Loader\Yaml as BaseLoader;

class Yaml extends BaseLoader
{
    protected $cache = [];

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
