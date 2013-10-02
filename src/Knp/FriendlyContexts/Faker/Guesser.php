<?php

namespace Knp\FriendlyContexts\Faker;

use Faker\Factory;

class Guesser
{
    public function test()
    {
        $faker = Factory::create();

        var_dump($faker->getProviders());
    }
}
