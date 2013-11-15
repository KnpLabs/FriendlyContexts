<?php

namespace Knp\FriendlyContexts\Dictionary;

use Faker\Generator;
use Faker\Provider\Base;

trait Providable
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

    public function setParent(Base $parent)
    {
        $this->parent = $parent;

        return $this;
    }

    public function supportsParent(Base $parent)
    {
        $rfl = new \ReflectionClass($parent);

        while (null !== $rfl && $rfl->getName() !== "Faker\Provider\Base") {
            $name = $rfl->getName();
            if ($this instanceof $name) {
                return true;
            }
            $rfl = $rfl->getParentClass();
        }

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
        if (false === $this->isFakable($property)) {
            throw new \Exception(
                sprintf(
                    'The property "%s" is not fakable by the provider named "%s"',
                    $property,
                    $this->getName()
                )
            );
        }

        $object = method_exists($this, $property) ? $this : null;

        if (null !== $this->parent) {
            if ($this->hasFakerMethod($this->parent, $property)) {
                $object = $this->parent;
            }
        }

        $method = $this->getFakerMethod($object, $property);

        if ($method->isStatic()) {

            $method->invokeArgs(null, $args);
        }

        return $method->invokeArgs($object, $args);
    }

    abstract public function getName();

    protected function hasFakerMethod(Base $object, $method)
    {
        $rfl = new \ReflectionClass($object);

        return $rfl->hasMethod($method);
    }

    protected function getFakerMethod(Base $object, $method)
    {
        $rfl = new \ReflectionClass($object);

        return $rfl->getMethod($method);
    }
}
