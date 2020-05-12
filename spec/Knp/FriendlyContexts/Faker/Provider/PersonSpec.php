<?php

namespace spec\Knp\FriendlyContexts\Faker\Provider;

use Faker\Generator;
use Faker\Provider\Person;
use Faker\Provider\Uuid;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PersonSpec extends ObjectBehavior
{
    function let(Generator $generator, Person $person)
    {
        $this->beConstructedWith($generator);
        $this->setParent($person);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Faker\Provider\Person');
    }

    function it_should_return_parent_provider($person)
    {
        $this->getParent()->shouldReturn($person);
    }

    function it_should_supports_Person_original_provider($person)
    {
        $this->supportsParent($person)->shouldReturn(true);
    }

    function it_should_not_supports_non_Person_original_provider(Uuid $uuid)
    {
        $this->supportsParent($uuid)->shouldReturn(false);
    }
}
