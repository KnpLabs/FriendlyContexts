<?php

namespace spec\Knp\FriendlyContexts\Context\Initializer;

use Knp\FriendlyContexts\Context\EntityContext;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use stdClass;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FriendlyInitializerSpec extends ObjectBehavior
{
    function let(ContainerInterface $container)
    {
        $this->beConstructedWith([], $container);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Context\Initializer\FriendlyInitializer');
    }

    function it_should_supports_contexts(EntityContext $context1)
    {
        $this->supports($context1)->shouldReturn(true);
    }

    function it_should_not_supports_non_contexts(stdClass $constext2)
    {
        $this->supports($constext2)->shouldReturn(false);
    }
}
