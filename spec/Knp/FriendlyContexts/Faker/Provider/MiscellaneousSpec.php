<?php

namespace spec\Knp\FriendlyContexts\Faker\Provider;

use Faker\Generator;
use Faker\Provider\Miscellaneous;
use Faker\Provider\Person;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MiscellaneousSpec extends ObjectBehavior
{
    function let(Generator $generator, Miscellaneous $miscellaneous)
    {
        $this->beConstructedWith($generator);
        $this->setParent($miscellaneous);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Faker\Provider\Miscellaneous');
    }

    function it_should_return_parent_provider($miscellaneous)
    {
        $this->getParent()->shouldReturn($miscellaneous);
    }

    function it_should_supports_Miscellaneous_original_provider($miscellaneous)
    {
        $this->supportsParent($miscellaneous)->shouldReturn(true);
    }

    function it_should_not_supports_non_Miscellaneous_original_provider(Person $person)
    {
        $this->supportsParent($person)->shouldReturn(false);
    }
}
