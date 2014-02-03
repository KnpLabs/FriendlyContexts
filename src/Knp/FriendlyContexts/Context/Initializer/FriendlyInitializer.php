<?php

namespace Knp\FriendlyContexts\Context\Initializer;

use Behat\Behat\Context\Initializer\ContextInitializer;
use Behat\Behat\Context\Context as ContextInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Knp\FriendlyContexts\Context\Context;

class FriendlyInitializer implements ContextInitializer
{
    protected $config;
    protected $container;

    public function __construct($config, ContainerInterface $container)
    {
        $this->config    = $config;
        $this->container = $container;
    }

    public function supports($context)
    {
        return $context instanceof Context;
    }

    public function initializeContext(ContextInterface $context)
    {
        if (false === $this->supports($context)) {

            return;
        }

        $context->initialize($this->config, $this->container);
    }
}
