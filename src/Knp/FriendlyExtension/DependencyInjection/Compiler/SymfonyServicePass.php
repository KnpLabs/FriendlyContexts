<?php

namespace Knp\FriendlyExtension\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use \ReflectionClass;

class SymfonyServicePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $services = $container->findTaggedServiceIds('friendly.symfony.service');

        foreach ($services as $id => $options) {
            $options = current($options);
            if (null === $kernel = $this->getKernel($container)) {
                $container->set($id, (new ReflectionClass($options['class']))->newInstanceWithoutConstructor());
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
