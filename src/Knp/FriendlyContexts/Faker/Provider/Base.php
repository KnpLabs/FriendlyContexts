<?php

namespace Knp\FriendlyContexts\Faker\Provider;

use Faker\Provider\Base as FakerProvider;

class Base extends FakerProvider
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
        if (false === $this->isFakable($property)) {
            throw new \Exception(
                sprintf(
                    'The property "%s" is not fakable by the provider "%s"',
                    $property,
                    get_class($this)
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
