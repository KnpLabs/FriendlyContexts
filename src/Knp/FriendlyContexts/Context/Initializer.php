<?php

namespace Knp\FriendlyContexts\Context;

use Behat\Behat\Context\Initializer\InitializerInterface;
use Behat\Behat\Context\ContextInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Knp\FriendlyContexts\Context\Context;

class Initializer implements InitializerInterface
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function supports(ContextInterface $context)
    {
        return $context instanceof Context;
    }

    public function initialize(ContextInterface $context)
    {
        $context->initialize($this->container);
    }
}
