<?php

namespace Knp\FriendlyContexts\Context;

use Behat\Behat\Context\BehatContext as BaseBehatContext;
use Knp\FriendlyContexts\Dictionary\Backgroundable;
use Knp\FriendlyContexts\Dictionary\Contextable;
use Knp\FriendlyContexts\Dictionary\Symfony;
use Knp\FriendlyContexts\Dictionary\Taggable;

abstract class BehatContext extends BaseBehatContext
{
    use Backgroundable,
        Contextable,
        Symfony,
        Taggable;
}
