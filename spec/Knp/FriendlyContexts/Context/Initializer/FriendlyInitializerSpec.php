<?php

namespace spec\Knp\FriendlyContexts\Context\Initializer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FriendlyInitializerSpec extends ObjectBehavior
{
    /**
     * @param Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param Knp\FriendlyContexts\Context\EntityContext $context1
     * @param StdClass $constext2
     **/
    function let($container)
    {
        $this->beConstructedWith([], $container);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Context\Initializer\FriendlyInitializer');
    }

    function it_should_supports_contexts($context1)
    {
        $this->supports($context1)->shouldReturn(true);
    }

    function it_should_not_supports_non_contexts($constext2)
    {
        $this->supports($constext2)->shouldReturn(false);
    }
}
