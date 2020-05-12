<?php

namespace spec\Knp\FriendlyContexts\Faker\Provider;

use Faker\Generator;
use Faker\Provider\Address;
use Faker\Provider\Person;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AddressSpec extends ObjectBehavior
{
    function let(Generator $generator, Address $address)
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
    
    function it_should_not_supports_non_Address_original_provider(Person $person)
    {
        $this->supportsParent($person)->shouldReturn(false);
    }
}
