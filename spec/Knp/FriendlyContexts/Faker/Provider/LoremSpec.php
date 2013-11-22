<?php

namespace spec\Knp\FriendlyContexts\Faker\Provider;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LoremSpec extends ObjectBehavior
{
    /**
     * @param Faker\Generator $generator
     * @param Faker\Provider\Lorem $lorem
     **/
    function let($generator, $lorem)
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

    /**
     * @param Faker\Provider\Person $person
     **/
    function it_should_not_supports_non_Lorem_original_provider($person)
    {
        $this->supportsParent($person)->shouldReturn(false);
    }
}
