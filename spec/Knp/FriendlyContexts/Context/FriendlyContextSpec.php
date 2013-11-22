<?php

namespace spec\Knp\FriendlyContexts\Context;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FriendlyContextSpec extends ObjectBehavior
{
    /**
     * @param Symfony\Component\DependencyInjection\ContainerInterface $container
     **/
    function let($container) {}

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Context\FriendlyContext');
    }

    function it_should_has_no_child_context()
    {
        $this->getSubcontexts()->shouldReturn([]);
    }

    function it_should_support_entity_context($container)
    {
        $config = [ 'Contexts' => [ 'Entity' => null ] ];

        $this->initialize($config, $container)->shouldReturn(null);

        $this->getSubcontext('entity')->shouldHaveType('Knp\FriendlyContexts\Context\EntityContext');
    }

    function it_should_not_support_unkown_context($container)
    {
        $config = [ 'Contexts' => [ 'NoContext' => null ] ];

        $this->initialize($config, $container)->shouldReturn(null);

        $this->getSubcontexts()->shouldReturn([]);
    }
}
