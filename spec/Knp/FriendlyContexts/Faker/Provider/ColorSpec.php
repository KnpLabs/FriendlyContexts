<?php

namespace spec\Knp\FriendlyContexts\Faker\Provider;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ColorSpec extends ObjectBehavior
{
    /**
     * @param Faker\Generator $generator
     * @param Faker\Provider\Color $color
     **/
    function let($generator, $color)
    {
        $this->beConstructedWith($generator);
        $this->setParent($color);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Faker\Provider\Color');
    }

    function it_should_return_parent_provider($color)
    {
        $this->getParent()->shouldReturn($color);
    }

    function it_should_supports_Color_original_provider($color)
    {
        $this->supportsParent($color)->shouldReturn(true);
    }

    /**
     * @param Faker\Provider\Person $person
     **/
    function it_should_not_supports_non_Color_original_provider($person)
    {
        $this->supportsParent($person)->shouldReturn(false);
    }
}
