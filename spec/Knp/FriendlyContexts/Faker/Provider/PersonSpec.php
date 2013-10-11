<?php

namespace spec\Knp\FriendlyContexts\Faker\Provider;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Faker\Generator;

class PersonSpec extends ObjectBehavior
{
    /**
     * @param Faker\Generator $generator
     * @param Faker\Provider\Person $goodProvider
     * @param Faker\Provider\Color $badProvider
     **/
    function let(Generator $generator, $goodProvider, $badProvider)
    {
        $this->beConstructedWith($generator);
        $this->setParentProvider($goodProvider);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Faker\Provider\Person');
        $this->getParentProvider()->shouldHaveType('Faker\Provider\Person');
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
        $goodProvider->name()->willReturn('the name');
        $this->fake('name')->shouldReturn('the name');
        $this->fake('fullname')->shouldReturn('the name');
    }
}
