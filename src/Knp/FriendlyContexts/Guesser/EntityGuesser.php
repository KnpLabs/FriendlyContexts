<?php

namespace Knp\FriendlyContexts\Guesser;

use Knp\FriendlyContexts\Record\Collection\Bag;

class EntityGuesser extends AbstractGuesser implements GuesserInterface
{
    public function __construct(Bag $bag)
    {
        $this->bag = $bag;
    }

    public function supports(array $mapping)
    {
        if (array_key_exists('targetEntity', $mapping)) {

            return $this->bag->getCollection($mapping['targetEntity'])->count() > 0;
        }

        return false;
    }

    public function transform($str, array $mapping)
    {
        $str = strlen((string) $str) ? $str : null;

        if (null !== $record = $this->bag->getCollection($mapping['targetEntity'])->search($str)) {

            return $record->getEntity();
        }

        return null;
    }

    public function fake(array $mapping)
    {
        $collection = $this->bag->getCollection($mapping['targetEntity']);

        if (0 === $collection->count()) {
            throw new \Exception(sprintf('There is no record for "%s"', $mapping['targetEntity']));
        }

        $records = array_values($collection->all());

        return $records[array_rand($records)]->getEntity();
    }

    public function getName()
    {
        return 'entity';
    }
}
