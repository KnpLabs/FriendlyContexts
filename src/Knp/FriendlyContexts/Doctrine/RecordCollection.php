<?php

namespace Knp\FriendlyContexts\Doctrine;

use Knp\FriendlyContexts\Reflection\ObjectReflector;

class RecordCollection
{

    protected $reflector;
    protected $referencial;
    protected $headers;
    protected $records;

    public function __construct(ObjectReflector $reflector)
    {
        $this->reflector = $reflector;
        $this->headers   = [];
        $this->records   = [];
    }

    public function support($entity)
    {
        if (null !== $this->referencial) {
            $name = $this->referencial;

            return $this->reflector->isInstanceOf($entity, $name);
        }
        $this->setReferencial($entity);

        return true;
    }

    public function getReferencial()
    {
        return $this->referencial;
    }

    public function setReferencial($entity)
    {
        $this->referencial = is_object($entity) ? $this->reflector->getClassLongName($entity) : $entity;

        return $this;
    }

    public function attach($entity, $values)
    {
        $this->mergeHeaders(array_keys($values));

        $record = new Record($this->reflector, $this);
        $record->attach($entity, $values);

        $this->records[] = $record;

        return $record;
    }

    public function search($value)
    {
        foreach ($this->headers as $header) {
            foreach ($this->records as $record) {
                if (null !== $record->get($header) && $value === $record->get($header)) {
                    return $record;
                }
            }
        }
    }

    protected function mergeHeaders($headers)
    {
        foreach ($headers as $header) {
            if (!in_array($header, $this->headers)) {
                $this->headers[] = $header;
            }
        }
    }
}
