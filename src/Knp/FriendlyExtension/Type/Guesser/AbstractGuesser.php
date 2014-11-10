<?php

namespace Knp\FriendlyExtension\Type\Guesser;

use Knp\FriendlyExtension\Faker\Provider\Base;
use Knp\FriendlyExtension\Type\Guesser\GuesserInterface;

abstract class AbstractGuesser implements GuesserInterface
{
    protected $fakers = [];

    public function addFaker(Base $faker)
    {
        if (null !== $faker->getParent()) {
            $this->fakers[] = $faker;
        }
    }
}
