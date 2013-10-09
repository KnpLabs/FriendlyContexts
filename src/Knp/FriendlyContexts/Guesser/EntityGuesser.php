<?php

namespace Knp\FriendlyContexts\Guesser;

class EntityGuesser extends AbstractGuesser implements GuesserInterface
{
    public function supports($mapping)
    {
        if (array_key_exists('targetEntity', $mapping)) {

            return $this->getRecordBag()->getCollection($mapping['targetEntity'])->count() > 0;
        }

        return false;
    }

    public function transform($str, $mapping)
    {
        if (null !== $record = $this->getRecordBag()->getCollection($mapping['targetEntity'])->search($str)) {

            return $record->getEntity();
        }

        return null;
    }

    public function getName()
    {
        return 'entity';
    }
}
