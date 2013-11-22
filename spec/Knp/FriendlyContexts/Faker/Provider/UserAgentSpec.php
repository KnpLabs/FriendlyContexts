<?php

namespace spec\Knp\FriendlyContexts\Faker\Provider;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UserAgentSpec extends ObjectBehavior
{
    /**
     * @param Faker\Generator $generator
     * @param Faker\Provider\UserAgent $useragent
     **/
    function let($generator, $useragent)
    {
        $this->beConstructedWith($generator);
        $this->setParent($useragent);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Faker\Provider\UserAgent');
    }

    function it_should_return_parent_provider($useragent)
    {
        $this->getParent()->shouldReturn($useragent);
    }

    function it_should_supports_UserAgent_original_provider($useragent)
    {
        $this->supportsParent($useragent)->shouldReturn(true);
    }

    /**
     * @param Faker\Provider\Person $person
     **/
    function it_should_not_supports_non_UserAgent_original_provider($person)
    {
        $this->supportsParent($person)->shouldReturn(false);
    }
}
