<?php

namespace Knp\FriendlyContexts\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class KernelPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $config = $container->getParameter('friendly.parameters');

        foreach ($config['symfony_kernel'] as $key => $value) {
            $container->setParameter(sprintf('friendly.symfony.kernel.%s', strtolower($key)), $value);
        }

        $basePath = $container->getParameter('behat.paths.base');

        $bootstrapPath = $container->getParameter('friendly.symfony.kernel.bootstrap');

        if (file_exists($bootstrap = $basePath.DIRECTORY_SEPARATOR.$bootstrapPath)) {
            require_once($bootstrap);
        } elseif (file_exists($bootstrapPath)) {
            require_once($bootstrapPath);
        }

        $kernelPath = $container->getParameter('friendly.symfony.kernel.path');
        if (file_exists($kernel = $basePath.DIRECTORY_SEPARATOR.$kernelPath)) {
            require_once($kernel);
        } elseif (file_exists($kernelPath)) {
            require_once($kernelPath);
        }

        $kernel = $container->get('friendly.symfony.kernel');
        $kernel->boot();
    }
}
