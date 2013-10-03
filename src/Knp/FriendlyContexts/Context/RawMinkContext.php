<?php

namespace Knp\FriendlyContexts\Context;

use Behat\MinkExtension\Context\RawMinkContext as BaseRawMinkContext;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Knp\FriendlyContexts\Dictionary\Backgroundable;
use Knp\FriendlyContexts\Dictionary\Contextable;
use Knp\FriendlyContexts\Dictionary\Facadable;
use Knp\FriendlyContexts\Dictionary\Symfony;
use Knp\FriendlyContexts\Dictionary\Taggable;
use Knp\FriendlyContexts\FacadeProvider;

abstract class RawMinkContext extends BaseRawMinkContext implements KernelAwareInterface
{
    use Backgroundable,
        Contextable,
        Facadable,
        Symfony,
        Taggable;

    protected $options = [];

    public function __construct(array $options = [])
    {
        $this->options = array_merge(
            $this->getDefaultOptions(),
            $options
        );

        $this->setFacadeProvider(new FacadeProvider($options));
    }

    protected function getDefaultOptions()
    {
        return [ ];
    }
}
