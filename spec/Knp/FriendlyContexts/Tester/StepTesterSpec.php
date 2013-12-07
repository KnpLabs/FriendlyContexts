<?php

namespace spec\Knp\FriendlyContexts\Tester;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class StepTesterSpec extends ObjectBehavior
{
    /**
     * @param Symfony\Component\DependencyInjection\ContainerInterface $container
     **/
    function let($container)
    {
        $this->beConstructedWith($container);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Tester\StepTester');
    }
}
