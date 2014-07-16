<?php

namespace spec\Knp;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FriendlyExtensionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyExtension');
    }
}
