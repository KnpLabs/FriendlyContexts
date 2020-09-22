<?php

namespace spec\Knp\FriendlyContexts\DependencyInjection\Compiler;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class FormatGuesserPassSpec extends ObjectBehavior
{
    function let(ContainerBuilder $container, Definition $manager, Definition $string, Definition $int, Definition $boolean)
    {
        $services = [
            'friendly.guesser.string'  => null,
            'friendly.guesser.int'     => null,
            'friendly.guesser.boolean' => null,
        ];

        $stringFaker = [
            'friendly.faker.provider.address'  => null,
            'friendly.faker.provider.person'   => null,
            'friendly.faker.provider.internet' => null,
        ];

        $intFaker = [
            'friendly.faker.provider.payment'  => null,
        ];

        $container->findTaggedServiceIds('friendly.format.guesser')->willReturn($services);
        $container->findTaggedServiceIds('friendly.guesser.string.faker')->willReturn($stringFaker);
        $container->findTaggedServiceIds('friendly.guesser.int.faker')->willReturn($intFaker);
        $container->findTaggedServiceIds('friendly.guesser.boolean.faker')->willReturn([]);
        $container->getDefinition('friendly.guesser.manager')->willReturn($manager);
        $container->getDefinition('friendly.guesser.string')->willReturn($string);
        $container->getDefinition('friendly.guesser.int')->willReturn($int);
        $container->getDefinition('friendly.guesser.boolean')->willReturn($boolean);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\DependencyInjection\Compiler\FormatGuesserPass');
    }

    function it_should_initialize_providers($container, $manager, $string, $int, $boolean)
    {
        $manager->addMethodCall('addGuesser', Argument::any())->shouldBeCalled();
        $string->addMethodCall('addFaker', Argument::any())->shouldBeCalled();
        $int->addMethodCall('addFaker', Argument::any())->shouldBeCalled();
        $boolean->addMethodCall('addFaker', Argument::any())->shouldNotBeCalled();

        $this->process($container)->shouldReturn(null);
    }
}
