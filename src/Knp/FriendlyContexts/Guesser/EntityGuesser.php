<?php

namespace Knp\FriendlyContexts\Guesser;

use Knp\FriendlyContexts\Record\Collection\Bag;

class EntityGuesser extends AbstractGuesser implements GuesserInterface
{
    public function __construct(Bag $bag)
    {
        $this->bag = $bag;
    }

    public function supports($mapping)
    {
        if (array_key_exists('targetEntity', $mapping)) {
            return $this->bag->getCollection($mapping['targetEntity'])->count() > 0;
        }

        return false;
    }

    public function transform($str, $mapping)
    {
        if (null !== $record = $this->bag->getCollection($mapping['targetEntity'])->search($str)) {
            return $record->getEntity();
        }

        return null;
    }

    public function getName()
    {
        return 'entity';
    }
}
