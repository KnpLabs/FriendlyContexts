<?php

namespace Knp\FriendlyContexts\Context;

use Behat\Behat\Context\BehatContext as BaseBehatContext;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Knp\FriendlyContexts\Dictionary\Backgroundable;
use Knp\FriendlyContexts\Dictionary\Containable;
use Knp\FriendlyContexts\Dictionary\Taggable;

abstract class BehatContext extends BaseBehatContext implements KernelAwareInterface
{
    use Backgroundable,
        Containable,
        Taggable;

    protected $options = [];

    public function __construct(array $options = [])
    {
        $this->options = array_merge(
            $this->getDefaultOptions(),
            $options
        );
    }

    protected function getDefaultOptions()
    {
        return [ ];
    }
}
