<?php

namespace Knp\FriendlyContexts\Record\Collection;

use Knp\FriendlyContexts\Record\Collection;
use Knp\FriendlyContexts\Dictionary\FacadableInterface;
use Knp\FriendlyContexts\Dictionary\Facadable;

class Bag implements FacadableInterface
{
    use Facadable;

    protected $collections = [];

    public function get($entity)
    {
        foreach ($this->collections as $collection) {
            if ($collection->support($entity)) {
                return $collection;
            }
        }

        $new = new Collection($this->getDeps('object.reflector'));
        $new->support($entity);

        return $this->collections[] = $new;;
    }

    public function count()
    {
        return count($this->collections);
    }
}
