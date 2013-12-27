<?php

namespace spec\Knp\FriendlyContexts\DependencyInjection\Compiler;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class KernelPassSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\DependencyInjection\Compiler\KernelPass');
    }
}
