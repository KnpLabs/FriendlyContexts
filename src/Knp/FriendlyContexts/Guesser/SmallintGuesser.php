<?php

namespace Knp\FriendlyContexts\Guesser;

class SmallintGuesser extends IntGuesser implements GuesserInterface
{
    public function supports(array $mapping)
    {
        $mapping = array_merge([ 'type' => null ], $mapping);

        return $mapping['type'] === 'smallint';
    }

    public function fake(array $mapping)
    {
        return $this->faker->fake('numberBetween', [0, 32000]);
    }

    public function getName()
    {
        return 'smallint';
    }
}
