<?php

namespace spec\Knp\FriendlyContexts\Faker\Provider;

use Faker\Generator;
use Faker\Provider\Internet;
use Faker\Provider\Person;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class InternetSpec extends ObjectBehavior
{
    function let(Generator $generator, Internet $internet)
    {
        $this->beConstructedWith($generator);
        $this->setParent($internet);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Faker\Provider\Internet');
    }

    function it_should_return_parent_provider($internet)
    {
        $this->getParent()->shouldReturn($internet);
    }

    function it_should_supports_Internet_original_provider($internet)
    {
        $this->supportsParent($internet)->shouldReturn(true);
    }

    function it_should_not_supports_non_Internet_original_provider(Person $person)
    {
        $this->supportsParent($person)->shouldReturn(false);
    }
}
