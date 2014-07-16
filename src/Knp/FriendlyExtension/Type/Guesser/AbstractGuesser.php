<?php

namespace Knp\FriendlyExtension\Type\Guesser;

use Knp\FriendlyExtension\Faker\Provider\Base;
use Knp\FriendlyExtension\Type\GuesserRegistry;
use Knp\FriendlyExtension\Type\Guesser\GuesserInterface;

abstract class AbstractGuesser implements GuesserInterface
{
    protected $manager;
    protected $fakers = [];

    public function getManager()
    {
        return $this->manager;
    }

    public function setManager(GuesserRegistry $manager)
    {
        $this->manager = $manager;

        return $this;
    }

    public function addFaker(Base $faker)
    {
        if (null !== $faker->getParent()) {
            $this->fakers[] = $faker;
        }
    }

    protected function get($name)
    {
        return $this->getManager()->getContainer()->get($name);
    }
}
