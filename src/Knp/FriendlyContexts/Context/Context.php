<?php

namespace Knp\FriendlyContexts\Context;

use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Knp\FriendlyContexts\Dictionary\Backgroundable;
use Knp\FriendlyContexts\Dictionary\Symfony;
use Knp\FriendlyContexts\Dictionary\Taggable;

abstract class Context extends RawMinkContext implements KernelAwareInterface
{
    use Backgroundable,
        Symfony,
        Taggable;

    protected $config = [];
    protected $container;

    public function initialize(array $config, ContainerInterface $container)
    {
        $this->config    = array_merge($this->getDefaultOptions(), $config);
        $this->container = $container;
    }

    protected function getRecordBag()
    {
        return $this->get('friendly.context.record.bag');
    }

    protected function getEntityHydrator()
    {
        return $this->get('friendly.context.entity.hydrator');
    }

    protected function getEntityResolver()
    {
        return $this->get('friendly.context.entity.resolver');
    }

    protected function getTextFormater()
    {
        return $this->get('friendly.context.text.formater');
    }

    protected function getAsserter()
    {
        return $this->get('friendly.context.asserter');
    }

    protected function getGuesserManager()
    {
        return $this->get('friendly.context.guesser.manager');
    }

    protected function getObjectReflector()
    {
        return $this->get('friendly.context.object.reflector');
    }

    protected function get($service)
    {
        if ($this->container->has($service)) {
            return $this->container->get($service);
        }

        throw new \Exception(sprintf('Service named "%s" unknow.', $service));
    }

    protected function getDefaultOptions()
    {
        return [ ];
    }
}
