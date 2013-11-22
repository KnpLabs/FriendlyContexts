<?php

namespace spec\Knp\FriendlyContexts\Faker\Provider;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class InternetSpec extends ObjectBehavior
{
    /**
     * @param Faker\Generator $generator
     * @param Faker\Provider\Internet $internet
     **/
    function let($generator, $internet)
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

    /**
     * @param Faker\Provider\Person $person
     **/
    function it_should_not_supports_non_Internet_original_provider($person)
    {
        $this->supportsParent($person)->shouldReturn(false);
    }
}
