<?php

namespace Knp\FriendlyExtension\Context\Initializer;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;
use Knp\FriendlyExtension\Context\ContextInterface;
use Knp\FriendlyExtension\Context\Helper\Registry;

class FriendlyInitializer implements ContextInitializer
{
    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    public function initializeContext(Context $context)
    {
        if (false === $this->supports($context)) {

            return;
        }

        $context->setHelperRegistry($this->registry);
    }

    private function supports($context)
    {
        return $context instanceof ContextInterface;
    }
}
