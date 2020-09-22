<?php

namespace spec\Knp\FriendlyContexts\Faker\Provider;

use Faker\Generator;
use Faker\Provider\DateTime;
use Faker\Provider\Person;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DateTimeSpec extends ObjectBehavior
{
    function let(Generator $generator, DateTime $datetime)
    {
        $this->beConstructedWith($generator);
        $this->setParent($datetime);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Faker\Provider\DateTime');
    }

    function it_should_return_parent_provider($datetime)
    {
        $this->getParent()->shouldReturn($datetime);
    }

    function it_should_supports_DateTime_original_provider($datetime)
    {
        $this->supportsParent($datetime)->shouldReturn(true);
    }

    function it_should_not_supports_non_DateTime_original_provider(Person $person)
    {
        $this->supportsParent($person)->shouldReturn(false);
    }
}
