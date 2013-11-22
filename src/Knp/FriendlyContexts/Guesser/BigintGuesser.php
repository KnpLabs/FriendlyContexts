<?php

namespace Knp\FriendlyContexts\Guesser;

class BigintGuesser extends IntGuesser implements GuesserInterface
{
    public function supports(array $mapping)
    {
        $mapping = array_merge([ 'type' => null ], $mapping);

        return $mapping['type'] === 'bigint';
    }

    public function fake(array $mapping)
    {
        return current($this->fakers)->fake('randomNumber');
    }

    public function getName()
    {
        return 'bigint';
    }
}
