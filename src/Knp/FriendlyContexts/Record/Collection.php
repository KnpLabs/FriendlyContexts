<?php

namespace Knp\FriendlyContexts\Record;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Knp\FriendlyContexts\Reflection\ObjectReflector;

class Collection
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

    public function attach($entity, array $values = null)
    {
        $values = $values ?: $this->buildValues($entity);
        $this->mergeHeaders(array_keys($values));

        $record = new Record($this->reflector, $this);
        $record->attach($entity, $values);

        $this->records[] = $record;

        return $record;
    }

    public function search($value)
    {
        foreach ($this->records as $record) {
            if ((string) $record === $value) {
                return $record;
            }
        }

        foreach ($this->headers as $header) {
            foreach ($this->records as $record) {
                if (null !== $record->get($header) && $value === $record->get($header)) {
                    return $record;
                }
            }
        }
    }

    public function all()
    {
        return array_values($this->records);
    }

    public function count()
    {
        return count($this->records);
    }

    protected function mergeHeaders($headers)
    {
        foreach ($headers as $header) {
            if (!in_array($header, $this->headers)) {
                $this->headers[] = $header;
            }
        }
    }

    protected function buildValues($entity)
    {
        $result = [];

        foreach ($this->headers as $header) {
            try {
                $value = PropertyAccess::getPropertyAccessor()->setValue($entity, $header);
                if (is_scalar($value)) {
                    $result[$header] = $value;
                }
            } catch (\Exception $ex) {
                unset($ex);
            }
        }

        return $result;
    }
}
