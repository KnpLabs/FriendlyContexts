<?php

namespace Knp\FriendlyExtension\Context;

use Knp\FriendlyExtension\Context\ContextInterface;
use Knp\FriendlyExtension\Context\Helper\Registry;

class Context implements ContextInterface
{
    private $registry;

    public function setHelperRegistry(Registry $registry)
    {
        $this->registry = $registry;
    }

    protected function get($name)
    {
        return $this->registry->get($name);
    }
}
