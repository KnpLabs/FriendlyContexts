<?php

namespace spec\Knp\FriendlyExtension\Alice;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\HttpKernel\KernelInterface;

class ClassLoaderSpec extends ObjectBehavior
{
    function let(KernelInterface $kernel, ContainerInterface $container, $service)
    {
        $kernel->getContainer()->willReturn($container);
        $container->has('other')->willReturn(false);
        $container->has('service')->willReturn(true);
        $container->get('service')->willReturn($service);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyExtension\Alice\ClassLoader');
    }

    function it_can_instanciate_a_class()
    {
        $this->instanciate('Knp\FriendlyExtension\Alice\ClassLoader')->shouldHaveType('Knp\FriendlyExtension\Alice\ClassLoader');
    }

    function it_cant_instanciate_onject_with_constructor()
    {
        $this
            ->shouldThrow(new \InvalidArgumentException('Constructor of Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException isn\'t supported because it needs arguments.'))
            ->duringInstanciate('Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException');
    }

    function it_can_instanciate_existing_services(KernelInterface $kernel, $service)
    {
        $this->beConstructedWith($kernel);
        $this->instanciate('@service')->shouldReturn($service);
    }

    function it_cant_instaciate_unknown_service(KernelInterface $kernel)
    {
        $this->beConstructedWith($kernel);
        $this
            ->shouldThrow(new ServiceNotFoundException('other'))
            ->duringInstanciate('@other')
        ;
    }

    function it_cant_instantiate_service_without_kernel()
    {
        $this
            ->shouldThrow(new \InvalidArgumentException('@service is not a valide class name'))
            ->duringInstanciate('@service')
        ;
    }
}
