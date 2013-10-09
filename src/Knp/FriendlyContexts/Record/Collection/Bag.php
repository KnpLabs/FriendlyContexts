<?php

namespace Knp\FriendlyContexts\Record\Collection;

use Knp\FriendlyContexts\Record\Collection;
use Knp\FriendlyContexts\Dictionary\Containable;

class Bag
{
    use Containable;

    protected $collections = [];

    public function getCollection($entity)
    {
        foreach ($this->collections as $collection) {
            if ($collection->support($entity)) {
                return $collection;
            }
        }

        $new = new Collection($this->getObjectReflector());
        $new->support($entity);

        return $this->collections[] = $new;;
    }

    public function count()
    {
        return count($this->collections);
    }
}
