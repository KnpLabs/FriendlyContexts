<?php

namespace Knp\FriendlyContexts\Doctrine;

use Knp\FriendlyContexts\Reflection\ObjectReflector;

class RecordCollection
{

    protected $reflector;
    protected $referencial;

    public function __construct(ObjectReflector $reflector)
    {
        $this->reflector = $reflector;
    }

    public function support($entity)
    {
        if (null !== $this->referencial) {
            $name = $this->getReferencialClassName();

            return $this->reflector->isInstanceOf($entity, $name);
        }
        $this->referencial = $entity;

        return true;
    }

    public function getReferencial()
    {
        return $this->referencial;
    }

    public function setReferencial($referencial)
    {
        $this->referencial = $referencial;

        return $this;
    }

    protected function getReferencialClassName()
    {
        if (is_string($this->referencial)) {

            return $this->referencial;
        }

        return $this->reflector->getClassLongName($this->referencial);
    }
}
