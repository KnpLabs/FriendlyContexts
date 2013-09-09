<?php

namespace spec\Knp\FriendlyContexts\Symfony\Profiler;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SwiftMailerSpec extends ObjectBehavior
{

    /**
     * @param Symfony\Component\HttpKernel\Profiler\Profiler $profiler
     **/
    function let($profiler)
    {
        $this->beConstructedWith($profiler);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Symfony\Profiler\SwiftMailer');
    }
}
