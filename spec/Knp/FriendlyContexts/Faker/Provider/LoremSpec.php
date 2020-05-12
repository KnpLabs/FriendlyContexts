<?php

namespace spec\Knp\FriendlyContexts\Faker\Provider;

use Faker\Generator;
use Faker\Provider\Lorem;
use Faker\Provider\Person;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LoremSpec extends ObjectBehavior
{
    function let(Generator $generator, Lorem $lorem)
    {
        $this->beConstructedWith($generator);
        $this->setParent($lorem);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Faker\Provider\Lorem');
    }

    function it_should_return_parent_provider($lorem)
    {
        $this->getParent()->shouldReturn($lorem);
    }

    function it_should_supports_Lorem_original_provider($lorem)
    {
        $this->supportsParent($lorem)->shouldReturn(true);
    }

    function it_should_not_supports_non_Lorem_original_provider(Person $person)
    {
        $this->supportsParent($person)->shouldReturn(false);
    }
}
