<?php

namespace Knp\FriendlyExtension\Command;

use Knp\FriendlyExtension\Command\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class CommandLoader
{
    protected $defaultCommands   = [];
    protected $defaultRegistered = false;

    public function registerDefaultCommands(Application $application)
    {
        if ($this->defaultRegistered) {
            return;
        }

        $this->defaultRegistered = true;

        foreach ($this->defaultCommands as $name => $command) {
            $application->add($command, $name);
        }
    }

    public function registerCommandsFromKernel(Application $application, KernelInterface $kernel)
    {
        $this->registerDefaultCommands($application);

        foreach ($kernel->getBundles() as $bundle) {
            $this->registerCommandsFromBundle($application, $bundle);
        }
    }

    public function registerCommandsFromBundle(Application $application, BundleInterface $bundle)
    {
        $this->registerDefaultCommands($application);
        $bundle->registerCommands($application);
    }

    public function addDefaultCommand($name, Command $command)
    {
        $this->defaultCommands[$name] = $command;
    }
}
