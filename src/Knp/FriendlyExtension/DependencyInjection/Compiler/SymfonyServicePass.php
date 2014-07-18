<?php

namespace Knp\FriendlyExtension\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SymfonyServicePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $services = $container->findTaggedServiceIds('friendly.symfony.service');

        foreach ($services as $id => $options) {
            if (null === $kernel = $this->getKernel($container)) {
                $container->set($id, (new ReflectionClass($options['class']))->createInstanceWithoutConstructor());
            } else {
                $kernel->boot();
                $container->set($id, $kernel->getContainer()->get($id));
            }
        }
    }

    private function getKernel(ContainerBuilder $container)
    {
        return $container->get('friendly.symfony.kernel', ContainerBuilder::NULL_ON_INVALID_REFERENCE);
    }
}
