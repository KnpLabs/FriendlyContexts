<?php

namespace Knp\FriendlyContexts\Alice;

use Symfony\Component\DependencyInjection\ContainerInterface;

class ProviderResolver
{
    private $container;
    private $providers;

    public function __construct(ContainerInterface $container, array $providers)
    {
        $this->container = $container;
        $this->providers = $providers;
    }

    public function all()
    {
        $services = [];

        foreach ($this->providers as $provider) {
            if (null !== $service = $this->getFromClass($provider)) {
                $services[] = $service;
                continue;
            }
            if (null !== $service = $this->getFromContainer($provider)) {
                $services[] = $service;
                continue;
            }
            if (null !== $service = $this->getFromKernel($provider)) {
                $services[] = $service;
                continue;
            }
            throw new \Exception(sprintf('Cannot find any class or service called "%s"', $provider));
        }

        return $services;
    }

    private function getFromClass($name)
    {
        if (class_exists($name)) {

            return new $name;
        }
    }

    private function getFromContainer($name)
    {
        if (0 !== strpos($name, '@')) {

            return;
        }

        $service = substr($name, 1);

        if (false === $this->container->has($service)) {

            return;
        }

        return $this->container->get($service);
    }

    private function getFromKernel($name)
    {
        if (0 !== strpos($name, '@')) {

            return;
        }

        $service = substr($name, 1);

        if (false === $this->container->has('friendly.symfony.kernel')) {

            return;
        }

        $kernel = $this->container->get('friendly.symfony.kernel');
        $kernel->boot();

        if (false === $kernel->getContainer()->has($service)) {

            return;
        }

        return $kernel->getContainer()->get($service);
    }
}
