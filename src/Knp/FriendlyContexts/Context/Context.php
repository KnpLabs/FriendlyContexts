<?php

namespace Knp\FriendlyContexts\Context;

use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Behat\Context\Context as ContextInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Knp\FriendlyContexts\Dictionary\Backgroundable;
use Knp\FriendlyContexts\Dictionary\Taggable;

abstract class Context implements ContextInterface
{
    use Backgroundable,
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
        return $this->get('friendly.record.bag');
    }

    protected function getEntityHydrator()
    {
        return $this->get('friendly.entity.hydrator');
    }

    protected function getEntityResolver()
    {
        return $this->get('friendly.entity.resolver');
    }

    protected function getTextFormater()
    {
        return $this->get('friendly.text.formater');
    }

    protected function getAsserter()
    {
        return $this->get('friendly.asserter');
    }

    protected function getGuesserManager()
    {
        return $this->get('friendly.guesser.manager');
    }

    protected function getObjectReflector()
    {
        return $this->get('friendly.object.reflector');
    }

    protected function getFeatureWalker()
    {
        return $this->get('friendly.feature.walker');
    }

    protected function getEntityManager()
    {
        return $this->get('doctrine')->getManager();
    }

    protected function get($service)
    {
        if ($this->container->has($service)) {
            return $this->container->get($service);
        }

        if ($this->container->get('friendly.symfony.kernel')->getContainer()->has($service)) {
            return $this->container->get('friendly.symfony.kernel')->getContainer()->get($service);
        }

        throw new \Exception(sprintf('Service named "%s" unknow.', $service));
    }

    protected function getDefaultOptions()
    {
        return [ ];
    }
}
