<?php

namespace Knp\FriendlyExtension\Alice;

use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\HttpKernel\KernelInterface;

class ClassLoader
{
    public function __construct(KernelInterface $kernel = null)
    {
        $this->kernel = $kernel;
    }

    public function instanciate($name)
    {
        if (null !== $instance = $this->getFromClassname($name)) {

            return $instance;
        }

        if (null !== $instance = $this->getFromKernel($name)) {

            return $instance;
        }

        $message = sprintf('%s is not a valide class name', $name);

        if (null !== $this->kernel) {
            $message .= ' or a valide service name';
        }

        throw new \InvalidArgumentException($message);
    }

    private function getFromClassname($class)
    {
        if ("@" === substr($class, 0, 1)) {

            return;
        }

        if (!class_exists($class)) {

            throw new \InvalidArgumentException(sprintf('Can\'t find class "%s"'), $class);
        }

        $rfl = new \ReflectionClass($class);
        $constructor = $rfl->getConstructor();

        if (null !== $constructor && 0 !== $constructor->getNumberOfRequiredParameters()) {
            throw new \InvalidArgumentException(sprintf('Constructor of %s isn\'t supported because it needs arguments.', $class));
        }

        return $rfl->newInstance();
    }

    private function getFromKernel($service)
    {
        if ("@" !== substr($service, 0, 1)) {

            return;
        }

        if (null === $this->kernel) {

            return;
        }

        $service = substr($service, 1);

        if (false === $this->kernel->getContainer()->has($service)) {

            throw new ServiceNotFoundException($service);
        }

        return $this->kernel->getContainer()->get($service);
    }
}
