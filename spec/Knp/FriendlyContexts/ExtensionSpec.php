<?php

namespace spec\Knp\FriendlyContexts;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ExtensionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Extension');
    }

    function it_should_provider_an_array_of_compilers()
    {
        $this->getCompilerPasses()->shouldBeArray();
    }
}
