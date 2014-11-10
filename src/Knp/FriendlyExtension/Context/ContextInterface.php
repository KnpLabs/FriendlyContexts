<?php

namespace Knp\FriendlyExtension\Context;

use Behat\Behat\Context\Context as BaseContextInterface;
use Knp\FriendlyExtension\Context\Helper\Registry;

interface ContextInterface extends BaseContextInterface
{
    public function setHelperRegistry(Registry $registry);
}
