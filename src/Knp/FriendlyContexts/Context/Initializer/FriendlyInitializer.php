<?php

namespace Knp\FriendlyContexts\Context\Initializer;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Knp\FriendlyContexts\Context\Context;

class FriendlyInitializer
{
    protected $config;
    protected $container;

    public function __construct(array $config, ContainerInterface $container)
    {
        $this->config    = $config;
        $this->container = $container;
    }

    public function supports($context)
    {
        return $context instanceof Context;
    }

    public function initialize($context)
    {
        $context->initialize($this->config, $this->container);
    }
}
