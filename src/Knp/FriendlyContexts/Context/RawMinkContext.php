<?php

namespace Knp\FriendlyContexts\Context;

use Behat\MinkExtension\Context\RawMinkContext as BaseRawMinkContext;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Knp\FriendlyContexts\Dictionary\Backgroundable;
use Knp\FriendlyContexts\Dictionary\Contextable;
use Knp\FriendlyContexts\Dictionary\Symfony;
use Knp\FriendlyContexts\Dictionary\Taggable;

abstract class RawMinkContext extends BaseRawMinkContext implements KernelAwareInterface
{
    use Backgroundable,
        Contextable,
        Symfony,
        Taggable;
}
