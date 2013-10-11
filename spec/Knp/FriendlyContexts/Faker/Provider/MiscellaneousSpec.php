<?php

namespace spec\Knp\FriendlyContexts\Faker\Provider;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Faker\Generator;

class MiscellaneousSpec extends ObjectBehavior
{
    /**
     * @param Faker\Generator $generator
     **/
    function let(Generator $generator)
    {
        $this->beConstructedWith($generator);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Faker\Provider\Miscellaneous');
    }
}
