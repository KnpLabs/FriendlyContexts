<?php

namespace spec\Knp\FriendlyContexts\Faker\Provider;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PhoneNumberSpec extends ObjectBehavior
{
    /**
     * @param Faker\Generator $generator
     * @param Faker\Provider\PhoneNumber $phonenumber
     **/
    function let($generator, $phonenumber)
    {
        $this->beConstructedWith($generator);
        $this->setParent($phonenumber);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Faker\Provider\PhoneNumber');
    }

    function it_should_return_parent_provider($phonenumber)
    {
        $this->getParent()->shouldReturn($phonenumber);
    }

    function it_should_supports_PhoneNumber_original_provider($phonenumber)
    {
        $this->supportsParent($phonenumber)->shouldReturn(true);
    }

    /**
     * @param Faker\Provider\Person $person
     **/
    function it_should_not_supports_non_PhoneNumber_original_provider($person)
    {
        $this->supportsParent($person)->shouldReturn(false);
    }
}
