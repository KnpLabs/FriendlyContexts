<?php

namespace spec\Knp\FriendlyContexts\DependencyInjection\Compiler;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ConfigPassSpec extends ObjectBehavior
{
    /**
     * @param Symfony\Component\DependencyInjection\ContainerBuilder $container
     **/
    function let($container)
    {
        $container->setParameter(Argument::cetera())->willReturn(null);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\DependencyInjection\Compiler\ConfigPass');
    }

    function it_should_load_all_parameters($container)
    {
        $config = [
            'p1' => 'test',
            'p2' => [
                'p3' => 'toto',
                'p4' => 'tata',
            ],
        ];
        $container->getParameter('friendly.parameters')->willReturn($config);

        $container->setParameter('friendly.p1', 'test')->shouldBeCalled();
        $container->setParameter('friendly.p2', [ 'p3' => 'toto', 'p4' => 'tata'])->shouldBeCalled();
        $container->setParameter('friendly.p2.p3', 'toto')->shouldBeCalled();
        $container->setParameter('friendly.p2.p4', 'tata')->shouldBeCalled();

        $this->process($container);
    }
}
