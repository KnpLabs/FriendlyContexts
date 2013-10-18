<?php

namespace Knp\FriendlyContexts\Context;

use Behat\Behat\Context\Initializer\InitializerInterface;
use Behat\Behat\Context\ContextInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Initializer implements InitializerInterface
{
    protected $config;
    protected $container;

    public function __construct(array $config, ContainerInterface $container)
    {
        $this->config    = $config;
        $this->container = $container;
    }

    public function supports(ContextInterface $context)
    {
        return $context instanceof FriendlyContext;
    }

    public function initialize(ContextInterface $context)
    {
        $context->initialize($this->config, $this->container);
    }
}
