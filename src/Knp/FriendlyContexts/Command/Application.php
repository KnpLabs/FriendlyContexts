<?php

namespace Knp\FriendlyContexts\Command;

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Console\Command\Command;

class Application extends BaseApplication
{
    private $kernel;
    private $loadedCommands;

    public function __construct(KernelInterface $kernel = null)
    {
        $this->kernel = $kernel;
        $this->loadedCommands = [];

        parent::__construct('Symfony');
    }

    public function getKernel()
    {
        return $this->kernel;
    }

    public function add(Command $command, $name = null)
    {
        $this->loadedCommands[$name ?: $command->getName()] = $command;
        $command->setApplication($this);
    }

    public function getCommands()
    {
        return $this->loadedCommands;
    }

    public function hasCommand($name)
    {
        return array_key_exists($name, $this->loadedCommands);
    }

    public function getCommand($name)
    {
        return $this->loadedCommands[$name];
    }

    public function find($name)
    {
        return $this->getCommand($name);
    }
}
