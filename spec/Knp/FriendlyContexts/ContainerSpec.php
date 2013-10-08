<?php

namespace spec\Knp\FriendlyContexts;

use PhpSpec\ObjectBehavior;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Knp\FriendlyContexts\Guesser\DatetimeGuesser;

class ContainerSpec extends ObjectBehavior
{
    /**
     * @param Symfony\Component\DependencyInjection\ContainerInterface $container
     **/
    function let(ContainerInterface $container)
    {
        $this->beConstructedWith($container, []);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Container');
    }

    function it_should_return_true_if_object_is_containable()
    {
        $this->isContainable(new DatetimeGuesser)->shouldReturn(false);
    }
}
