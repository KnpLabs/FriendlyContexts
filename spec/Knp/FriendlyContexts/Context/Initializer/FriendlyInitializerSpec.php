<?php

namespace spec\Knp\FriendlyContexts\Context\Initializer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FriendlyInitializerSpec extends ObjectBehavior
{
    /**
     * @param Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param Knp\FriendlyContexts\Context\SmartContext $context1
     * @param Knp\FriendlyContexts\Context\EntityContext $context2
     * @param Behat\Behat\Context\ContextInterface $context3
     **/
    function let($container)
    {
        $this->beConstructedWith([], $container);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Context\Initializer\FriendlyInitializer');
    }

    function it_should_supports_contexts($context1, $context2)
    {
        $this->supports($context1)->shouldReturn(true);
        $this->supports($context2)->shouldReturn(true);
    }

    function it_should_not_supports_non_contexts($context3)
    {
        $this->supports($context3)->shouldReturn(false);
    }
}
