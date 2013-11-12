<?php

namespace spec\Knp\FriendlyContexts\Context;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class InitializerSpec extends ObjectBehavior
{
    /**
     * @param Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param Knp\FriendlyContexts\Context\FriendlyContext $context1
     * @param Knp\FriendlyContexts\Context\EntityContext $context2
     * @param Behat\Behat\Context\ContextInterface $context3
     **/
    function let($container)
    {
        $this->beConstructedWith([], $container);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Context\Initializer');
    }

    function it_should_supports_contexts($context1)
    {
        $this->supports($context1)->shouldReturn(true);
    }

    function it_should_not_supports_non_contexts($context2)
    {
        $this->supports($context2)->shouldReturn(false);
    }
}
