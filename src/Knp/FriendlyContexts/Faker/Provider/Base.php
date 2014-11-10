<?php

namespace Knp\FriendlyContexts\Faker\Provider;

use Faker\Provider\Base as FakerProvider;

abstract class Base extends FakerProvider
{
    protected $parent;

    public function initialise()
    {
        foreach ($this->generator->getProviders() as $provider) {
            if ($this->supportsParent($provider)) {
                $this->setParent($provider);
            }
        }
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function setParent(FakerProvider $parent)
    {
        $this->parent = $parent;

        return $this;
    }

    public function supportsParent($parent)
    {
        return false;
    }

    public function isFakable($property)
    {
        if (null !== $this->parent) {
            if ($this->hasFakerMethod($this->parent, $property)) {
                return true;
            }
        }

        return $this->hasFakerMethod($this, $property);
    }

    public function fake($property, array $args = [])
    {
        $object = method_exists($this, $property) ? $this : null;

        if ((null !== $this->parent) && ($this->hasFakerMethod($this->parent, $property))) {
            $object = $this->parent;
        }

        $method = $this->getFakerMethod($object, $property);

        return $method->invokeArgs($method->isStatic() ? null : $object, $args);
    }

    protected function hasFakerMethod($object, $method)
    {
        $rfl = new \ReflectionClass($object);

        return $rfl->hasMethod($method);
    }

    protected function getFakerMethod($object, $method)
    {
        $rfl = new \ReflectionClass($object);

        return $rfl->getMethod($method);
    }
}
