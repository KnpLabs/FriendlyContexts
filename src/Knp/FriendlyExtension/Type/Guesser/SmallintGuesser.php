<?php

namespace Knp\FriendlyExtension\Type\Guesser;

use Knp\FriendlyExtension\Type\Guesser\AbstractGuesser;
use Knp\FriendlyExtension\Type\Guesser\IntGuesser;

final class SmallintGuesser extends AbstractGuesser
{
    private $intGuesser;

    public function __construct(IntGuesser $intGuesser)
    {
        $this->intGuesser = $intGuesser;
    }

    public function supports(array $mapping)
    {
        $mapping = array_merge([ 'type' => null ], $mapping);

        return $mapping['type'] === 'smallint';
    }

    public function transform($str, array $mapping = null)
    {
        return $this->intGuesser->transform($str, $mapping);
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
