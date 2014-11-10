<?php

namespace Knp\FriendlyExtension\Context\Helper;

use Knp\FriendlyExtension\Context\Helper\Registry;

interface HelperInterface
{
    public function setRegistry(Registry $registry);
    public function getName();
    public function clear();
}
