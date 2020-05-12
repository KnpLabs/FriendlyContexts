<?php

namespace spec\Knp\FriendlyContexts\Faker\Provider;

use Faker\Generator;
use Faker\Provider\Person;
use Faker\Provider\PhoneNumber;
use PhpSpec\ObjectBehavior;

class PhoneNumberSpec extends ObjectBehavior
{
    function let(Generator $generator, PhoneNumber $phonenumber)
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

    function it_should_not_supports_non_PhoneNumber_original_provider(Person $person)
    {
        $this->supportsParent($person)->shouldReturn(false);
    }
}
