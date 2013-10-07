<?php

namespace Knp\FriendlyContexts\Dictionary;

use Knp\FriendlyContexts\Container;

trait Containable
{
    protected $container;

    protected function getContainer()
    {
        return $this->container;
    }

    public function setContainer(Container $container)
    {
        $this->container = $container;

        return $this;
    }

    protected function has($name)
    {
        return $this->container->has($name);
    }

    protected function get($name)
    {
        if ($this->container->has($name)) {
            return $this->container->get($name);
        } else {
            throw new \Exception(sprintf('Service witn name "%s" un found', $name));
        }
    }

    protected function set($name, $value)
    {
        $this->container->set($name, $value);

        return $this;
    }
}
