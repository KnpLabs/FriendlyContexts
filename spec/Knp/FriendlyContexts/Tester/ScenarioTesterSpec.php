<?php

namespace spec\Knp\FriendlyContexts\Tester;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ScenarioTesterSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Tester\ScenarioTester');
    }
}
