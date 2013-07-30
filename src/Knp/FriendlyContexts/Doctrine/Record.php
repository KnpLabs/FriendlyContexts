<?php

namespace Knp\FriendlyContexts\Doctrine;

use Knp\FriendlyContexts\Doctrine\RecordCollection;
use Knp\FriendlyContexts\Reflection\ObjectReflector;

class Record
{

    protected $reflector;
    protected $collection;
    protected $entity;
    protected $values;

    public function __construct(ObjectReflector $reflector, RecordCollection $collection)
    {
        $this->reflector = $reflector;
        $this->collection = $collection;
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
}