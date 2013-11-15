<?php

namespace Knp\FriendlyContexts\Guesser;

use Knp\FriendlyContexts\Faker\Provider\Miscellaneous;

class IntGuesser extends AbstractGuesser implements GuesserInterface
{
    public function __construct(Miscellaneous $faker)
    {
        $this->faker = $faker;
    }

    public function supports(array $mapping)
    {
        $mapping = array_merge([ 'type' => null ], $mapping);

        return $mapping['type'] === 'int';
    }

    public function transform($str, array $mapping = null)
    {
        return (int) $str;
    }

    public function fake(array $mapping)
    {
        return $this->faker->fake('numberBetween', [0, 2000000000]);
    }

    public function getName()
    {
        return 'int';
    }
}
