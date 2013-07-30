<?php

namespace Knp\FriendlyContexts\Doctrine;

use Knp\FriendlyContexts\Reflection\ObjectReflector;
use Knp\FriendlyContexts\Doctrine\RecordCollection;

class RecordCollectionBag
{
    protected $reflector;
    protected $collections = [];

    public function __construct(ObjectReflector $reflector)
    {
        $this->reflector = $reflector;
    }

    public function get($entity)
    {
        foreach ($this->collections as $collection) {
            if ($collection->support($entity)) {
                return $collection;
            }
        }

        $new = new RecordCollection($this->reflector);
        $new->support($entity);

        return $this->collections[] = $new;;
    }

    public function count()
    {
        return count($this->collections);
    }
}
