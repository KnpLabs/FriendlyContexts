<?php

namespace Knp\FriendlyContexts\Guesser;

class SmallintGuesser extends AbstractGuesser implements GuesserInterface
{
    public function supports(array $mapping)
    {
        $mapping = array_merge([ 'type' => null ], $mapping);

        return $mapping['type'] === 'smallint';
    }

    public function transform($str, array $mapping = null)
    {
        return (int) $str;
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
