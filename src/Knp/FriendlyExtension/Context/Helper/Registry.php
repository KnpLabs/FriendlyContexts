<?php

namespace Knp\FriendlyExtension\Context\Helper;

use Knp\FriendlyExtension\Context\Helper\HelperInterface;

class Registry
{
    private $helpers = [];

    public function addHelper(HelperInterface $helper)
    {
        $this->helpers[] = $helper;
        $helper->setRegistry($this);
    }

    public function get($name)
    {
        foreach ($this->helpers as $helper) {
            if ($name === $helper->getName()) {

                return $helper;
            }
        }

        throw new \Exception(sprintf('Unable to find a context helper named "%s"', $name));
    }
}
