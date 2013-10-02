<?php

namespace Knp\FriendlyContexts\Guesser;

class DatetimeGuesser implements GuesserInterface
{

    public function supportMapping($mapping)
    {
        $mapping = array_merge([ 'type' => null ], $mapping);

        return $mapping['type'] === 'datetime';
    }
}
