<?php

namespace spec\Knp\FriendlyContexts\Guesser;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class IntGuesserSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Guesser\IntGuesser');
    }
}
