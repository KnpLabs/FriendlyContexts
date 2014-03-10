<?php

namespace spec\Knp\FriendlyContexts\Context;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ApiContextSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Context\ApiContext');
    }

    function it_is_a_context()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Context\Context');
    }
}
