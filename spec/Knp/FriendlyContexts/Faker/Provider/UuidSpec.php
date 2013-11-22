<?php

namespace spec\Knp\FriendlyContexts\Faker\Provider;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UuidSpec extends ObjectBehavior
{
    /**
     * @param Faker\Generator $generator
     * @param Faker\Provider\Uuid $uuid
     **/
    function let($generator, $uuid)
    {
        $this->beConstructedWith($generator);
        $this->setParent($uuid);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Faker\Provider\Uuid');
    }

    function it_should_return_parent_provider($uuid)
    {
        $this->getParent()->shouldReturn($uuid);
    }

    function it_should_supports_Uuid_original_provider($uuid)
    {
        $this->supportsParent($uuid)->shouldReturn(true);
    }

    /**
     * @param Faker\Provider\Person $person
     **/
    function it_should_not_supports_non_Uuid_original_provider($person)
    {
        $this->supportsParent($person)->shouldReturn(false);
    }
}
