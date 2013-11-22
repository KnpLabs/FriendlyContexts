<?php

namespace spec\Knp\FriendlyContexts\Faker\Provider;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MiscellaneousSpec extends ObjectBehavior
{
    /**
     * @param Faker\Generator $generator
     * @param Faker\Provider\Miscellaneous $miscellaneous
     **/
    function let($generator, $miscellaneous)
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

    /**
     * @param Faker\Provider\Person $person
     **/
    function it_should_not_supports_non_Miscellaneous_original_provider($person)
    {
        $this->supportsParent($person)->shouldReturn(false);
    }
}
