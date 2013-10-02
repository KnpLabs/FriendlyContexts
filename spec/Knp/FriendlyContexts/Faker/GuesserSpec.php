<?php

namespace spec\Knp\FriendlyContexts\Faker;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GuesserSpec extends ObjectBehavior
{
    /**
     * @param Faker\Generator $faker
     **/
    function let($faker)
    {
        $this->test();
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Faker\Guesser');
    }
}
