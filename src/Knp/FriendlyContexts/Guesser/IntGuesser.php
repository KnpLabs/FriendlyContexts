<?php

namespace Knp\FriendlyContexts\Guesser;

class IntGuesser extends AbstractGuesser implements GuesserInterface
{
    public function supports(array $mapping)
    {
        $mapping = array_merge([ 'type' => null ], $mapping);

        return $mapping['type'] === 'integer';
    }

    public function transform($str, array $mapping = null)
    {
        return (int) round($str);
    }

    public function fake(array $mapping)
    {
        return current($this->fakers)->fake('numberBetween', [0, 2000000000]);
    }

    public function getName()
    {
        return 'int';
    }
}
