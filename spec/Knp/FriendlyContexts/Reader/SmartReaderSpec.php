<?php

namespace spec\Knp\FriendlyContexts\Reader;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SmartReaderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Reader\SmartReader');
    }
}
