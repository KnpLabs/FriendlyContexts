<?php

namespace Knp\FriendlyContexts\Dictionary;

use Faker\Provider\Base;

trait Providable
{
    protected $parentProvider;

    public function getParentProvider()
    {
        return $this->parentProvider;
    }

    public function setParentProvider(Base $parentProvider)
    {
        $this->parentProvider = $parentProvider;

        return $this;
    }

    public function supportsParentProvider(Base $parentProvider)
    {
        $rfl = new \ReflectionClass($parentProvider);

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
        if (null !== $this->parentProvider) {
            if ($this->hasFakerMethod($this->parentProvider, $property)) {
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

        if (null !== $this->parentProvider) {
            if ($this->hasFakerMethod($this->parentProvider, $property)) {
                $object = $this->parentProvider;
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
