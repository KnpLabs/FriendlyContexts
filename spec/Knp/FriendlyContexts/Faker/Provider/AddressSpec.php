<?php

namespace spec\Knp\FriendlyContexts\Faker\Provider;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Faker\Generator;
use Faker\Factory;
use Faker\Provider\Address;

class AddressSpec extends ObjectBehavior
{
    /**
     * @param Faker\Generator $generator
     * @param Faker\Provider\Address $goodProvider
     * @param Faker\Provider\Color $badProvider
     **/
    function let(Generator $generator, $goodProvider, $badProvider)
    {
        $this->beConstructedWith($generator);
        $this->setParentProvider(new Address(Factory::create()));
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Faker\Provider\Address');
        $this->getParentProvider()->shouldHaveType('Faker\Provider\Address');
    }

    function it_should_supports_provider($goodProvider)
    {
        $this->supportsParentProvider($goodProvider)->shouldReturn(true);
    }

    function it_should_not_supports_provider($badProvider)
    {
        $this->supportsParentProvider($badProvider)->shouldReturn(false);
    }

    function it_should_fake($goodProvider)
    {
        $this->fake('address')->shouldBeString();
        $this->fake('postcode')->shouldBeString();
    }
}
