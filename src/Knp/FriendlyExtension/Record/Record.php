<?php

namespace Knp\FriendlyExtension\Record;

use Knp\FriendlyExtension\Record\Collection;
use Knp\FriendlyExtension\Utils\ObjectReflector;

class Record
{

    protected $reflector;
    protected $collection;
    protected $entity;
    protected $values;

    public function __construct(ObjectReflector $reflector, Collection $collection)
    {
        $this->reflector = $reflector;
        $this->collection = $collection;
    }

    public function __toString()
    {
        return (string) $this->entity;
    }

    public function isStringable()
    {
        return method_exists($this->entity, '__toString');
    }

    public function attach($entity, $values = [])
    {
        if (!$this->collection->support($entity)) {
            throw new \InvalidArgumentException('Given entity is not supported by the collection');
        }

        $this->entity = $entity;
        $this->values = $values;
    }

    public function getEntity()
    {
        return $this->entity;
    }

    public function all()
    {
        return $this->values;
    }

    public function has($key)
    {
        return array_key_exists($key, $this->values);
    }

    public function get($key)
    {
        if ($this->has($key)) {
            return $this->values[$key];
        }
    }

    public function equals($key, $value)
    {
        if ($this->has($key)) {
            return $this->get($key) === $value;
        }

        return false;
    }
}
