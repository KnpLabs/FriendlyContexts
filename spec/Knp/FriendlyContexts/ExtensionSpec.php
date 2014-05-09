<?php

namespace spec\Knp\FriendlyContexts;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ExtensionSpec extends ObjectBehavior
{
    /**
     * @param Symfony\Component\DependencyInjection\ContainerBuilder $container
     **/
    function let($container)
    {
        $container->addResource(Argument::cetera())->willReturn(null);
        $container->setDefinition(Argument::cetera())->willReturn(null);
        $container->addCompilerPass(Argument::cetera())->willReturn(null);
        $container->setParameter(Argument::cetera())->willReturn(null);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Extension');
    }

    function it_should_load_all_parameters($container)
    {
        $config = [
            'p1' => 'test',
            'p2' => [
                'p3' => 'toto',
                'p4' => 'tata',
            ],
            'api' => [
                'base_url' => 'http://test.com'
            ]
        ];

        $container->hasParameter('mink.base_url')->willReturn(false);
        $container->setParameter('friendly.api.base_url', 'http://test.com')->shouldBeCalled();
        $container->setParameter('friendly.p1', 'test')->shouldBeCalled();
        $container->setParameter('friendly.p2', [ 'p3' => 'toto', 'p4' => 'tata'])->shouldBeCalled();
        $container->setParameter('friendly.p2.p3', 'toto')->shouldBeCalled();
        $container->setParameter('friendly.p2.p4', 'tata')->shouldBeCalled();

        $this->load($container, $config);
    }
}
