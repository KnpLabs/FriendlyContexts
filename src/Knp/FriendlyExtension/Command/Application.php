<?php

namespace Knp\FriendlyExtension\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application as BaseApplication;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\HttpKernel\KernelInterface;

class Application extends BaseApplication
{
    private $kernel;
    private $loadedCommands;

    public function __construct(KernelInterface $kernel = null)
    {
        $this->kernel         = $kernel;
        $this->loadedCommands = [];

        parent::__construct($this->kernel);
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
