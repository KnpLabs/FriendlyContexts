<?php

namespace Knp\FriendlyExtension\Context\Helper;

use Knp\FriendlyExtension\Context\Helper\HelperInterface;

abstract class AbstractHelper implements HelperInterface
{
    private $registry;

    public function setRegistry(Registry $registry)
    {
        $this->registry = $registry;
    }

    public function get($name)
    {
        return $this->registry->get($name);
    }
}
