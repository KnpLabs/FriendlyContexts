<?php

namespace Knp\FriendlyExtension\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\Kernel;

class KernelRegistrationPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $basePath = $container->getParameter('paths.base');

        $this->loadFileFromParameter($container, 'friendly.symfony_kernel.bootstrap');
        $this->loadFileFromParameter($container, 'friendly.symfony_kernel.path');

        if (null !== $class = $this->getKernelClass($container)) {
            $definition = new Definition($class);
            $definition
                ->addArgument($container->getParameter('friendly.symfony_kernel.env'))
                ->addArgument($container->getParameter('friendly.symfony_kernel.debug'))
            ;
            $container->setDefinition('friendly.symfony.kernel', $definition);
        } else {
            $container->removeDefinition('friendly.symfony.kernel');
        }
    }

    protected function loadFileFromParameter(ContainerBuilder $container, $parameter)
    {
        $base  = $container->getParameter('paths.base');
        $param = $container->getParameter($parameter);
        if (file_exists($file = $base.DIRECTORY_SEPARATOR.$param)) {
            require_once($file);
        } elseif (file_exists($param)) {
            require_once($param);
        }
    }

    protected function getKernelClass(ContainerBuilder $container)
    {
        $class = $container->getParameter('friendly.symfony_kernel.class');

        return class_exists($class) ? $class : null;
    }
}
