<?php

namespace Knp\FriendlyContexts\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class KernelPass implements CompilerPassInterface
{
    protected $config;

    public function __construct(array $config = array())
    {
        $this->config = $config;
    }

    public function process(ContainerBuilder $container)
    {
        $config = $this->config;

        foreach ($config['symfony_kernel'] as $key => $value) {
            $container->setParameter(sprintf('friendly.symfony_kernel.%s', strtolower($key)), $value);
        }

        $basePath = $container->getParameter('paths.base');

        $bootstrapPath = $container->getParameter('friendly.symfony_kernel.bootstrap');

        if (file_exists($bootstrap = $basePath.DIRECTORY_SEPARATOR.$bootstrapPath)) {
            require_once($bootstrap);
        } elseif (file_exists($bootstrapPath)) {
            require_once($bootstrapPath);
        }

        $kernelPath = $container->getParameter('friendly.symfony_kernel.path');
        if (file_exists($kernel = $basePath.DIRECTORY_SEPARATOR.$kernelPath)) {
            require_once($kernel);
        } elseif (file_exists($kernelPath)) {
            require_once($kernelPath);
        }

        $kernel = $container->get('friendly.symfony.kernel');
        $kernel->boot();
    }
}
