<?php

namespace Knp\FriendlyContexts\Context;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Knp\FriendlyContexts\Dictionary\Backgroundable;
use Knp\FriendlyContexts\Dictionary\Symfony;
use Knp\FriendlyContexts\Dictionary\Taggable;

abstract class Context extends RawMinkContext implements KernelAwareInterface
{
    use Backgroundable,
        Symfony,
        Taggable;

    protected $options = [];
    protected $container;

    public function initialize(ContainerInterface $container, array $options = [])
    {
        $this->container = $container;
        $this->options = array_merge($this->getDefaultOptions(), $options);
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
