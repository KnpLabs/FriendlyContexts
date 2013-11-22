<?php

namespace spec\Knp\FriendlyContexts\Faker\Provider;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DateTimeSpec extends ObjectBehavior
{
    /**
     * @param Faker\Generator $generator
     * @param Faker\Provider\DateTime $datetime
     **/
    function let($generator, $datetime)
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

    /**
     * @param Faker\Provider\Person $person
     **/
    function it_should_not_supports_non_DateTime_original_provider($person)
    {
        $this->supportsParent($person)->shouldReturn(false);
    }
}
