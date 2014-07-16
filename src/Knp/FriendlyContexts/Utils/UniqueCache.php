<?php

namespace Knp\FriendlyContexts\Utils;

class UniqueCache
{
    private $cache = [];

    public function exists($className, $field, $value)
    {
        if (!isset($this->cache[$className])) {
            return false;
        }

        if (!is_array($this->cache[$className]) || !isset($this->cache[$className][$field])) {
            return false;
        }

        if (!is_array($this->cache[$className][$field])) {
            return false;
        }

        foreach ($this->cache[$className][$field] as $cacheValue) {
            if ($value === $cacheValue) {
                return true;
            }
        }

        return false;
    }

    public function generate($className, $field, $callback)
    {
        do {
            $value = $callback();
        } while ($this->exists($className, $field, $value));

        if (!isset($this->cache[$className])) {
            $this->cache[$className] = [];
        }

        if (!isset($this->cache[$className][$field])) {
            $this->cache[$className][$field] = [];
        }

        $this->cache[$className][$field][] = $value;

        return $value;
    }

    public function clear()
    {
        $this->cache = [];
    }
}
