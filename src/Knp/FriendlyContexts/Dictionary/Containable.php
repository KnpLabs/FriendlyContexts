<?php

namespace Knp\FriendlyContexts\Dictionary;

use Knp\FriendlyContexts\Doctrine\EntityHydrator;
use Knp\FriendlyContexts\Doctrine\EntityResolver;
use Knp\FriendlyContexts\Record\Collection\Bag;
use Knp\FriendlyContexts\Tool\Asserter;
use Knp\FriendlyContexts\Tool\TextFormater;
use Knp\FriendlyContexts\Guesser\GuesserManager;
use Knp\FriendlyContexts\Reflection\ObjectReflector;

trait Containable
{
    use Symfony;

    protected function getRecordBag()
    {
        return $this->getOrRegister('friendly.context.record.bag', function() { return new Bag; });
    }

    protected function getEntityHydrator()
    {
        return $this->getOrRegister('friendly.context.entity.hydrator', function() { return new EntityHydrator; });
    }

    protected function getEntityResolver()
    {
        return $this->getOrRegister('friendly.context.entity.resolver', function() { return new EntityResolver; });
    }

    protected function getTextFormater()
    {
        return $this->getOrRegister('friendly.context.text.formater', function() { return new TextFormater; });
    }

    protected function getAsserter()
    {
        return $this->getOrRegister('friendly.context.asserter', function() { return new Asserter; });
    }

    protected function getGuesserManager()
    {
        return $this->getOrRegister('friendly.context.guesser.manager', function() { return new GuesserManager; });
    }

    protected function getObjectReflector()
    {
        return $this->getOrRegister('friendly.context.object.reflector', function() { return new ObjectReflector; });
    }

    protected function getOrRegister($name, $callback = null)
    {
        if ($this->getContainer()->has($name)) {
            return $this->getContainer()->get($name);
        }

        if (null !== $callback) {
            $service = $callback();
            $this->getContainer()->set($name, $service);
            if ($this->isContainable($service)) {
                $service->setKernel($this->getKernel());
            }

            return $service;
        }
    }

    protected function isContainable($service)
    {
        $rfl = new \ReflectionClass($service);

        $traits = [];
        while (false !== $rfl) {
            $traits = array_merge($traits, $rfl->getTraitNames());
            $rfl = $rfl->getParentClass();
        }

        return in_array('Knp\FriendlyContexts\Dictionary\Containable', $traits);
    }
}
