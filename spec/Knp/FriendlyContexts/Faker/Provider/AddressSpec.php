<?php

namespace spec\Knp\FriendlyContexts\Faker\Provider;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AddressSpec extends ObjectBehavior
{
    /**
     * @param Faker\Generator $generator
     * @param Faker\Provider\Address $address
     **/
    function let($generator, $address)
    {
        $this->beConstructedWith($generator);
        $this->setParent($address);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Faker\Provider\Address');
    }

    function it_should_return_parent_provider($address)
    {
        $this->getParent()->shouldReturn($address);
    }

    function it_should_supports_Address_original_provider($address)
    {
        $this->supportsParent($address)->shouldReturn(true);
    }

    /**
     * @param Faker\Provider\Person $person
     **/
    function it_should_not_supports_non_Address_original_provider($person)
    {
        $this->supportsParent($person)->shouldReturn(false);
    }
}
