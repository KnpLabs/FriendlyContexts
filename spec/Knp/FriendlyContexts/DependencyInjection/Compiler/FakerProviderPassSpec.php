<?php

namespace spec\Knp\FriendlyContexts\DependencyInjection\Compiler;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class FakerProviderPassSpec extends ObjectBehavior
{
    function let(ContainerBuilder $container, Definition $string, Definition $int, Definition $boolean)
    {
        $services = [
            'friendly.faker.provider.string'  => null,
            'friendly.faker.provider.int'     => null,
            'friendly.faker.provider.boolean' => null,
        ];

        $container->findTaggedServiceIds('friendly.faker.provider')->willReturn($services);
        $container->getDefinition('friendly.faker.provider.string')->willReturn($string);
        $container->getDefinition('friendly.faker.provider.int')->willReturn($int);
        $container->getDefinition('friendly.faker.provider.boolean')->willReturn($boolean);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\DependencyInjection\Compiler\FakerProviderPass');
    }

    function it_should_initialize_providers($container, $string, $int, $boolean)
    {
        $this->process($container)->shouldReturn(null);

        $string->addMethodCall('initialise')->shouldBeCalled();
        $int->addMethodCall('initialise')->shouldBeCalled();
        $boolean->addMethodCall('initialise')->shouldBeCalled();
    }
}
