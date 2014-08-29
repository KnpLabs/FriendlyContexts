<?php

namespace Knp\FriendlyExtension\Context\Helper;

use Knp\FriendlyExtension\Command\Application;
use Knp\FriendlyExtension\Command\CommandLoader;
use Knp\FriendlyExtension\Command\CommandTester;
use Knp\FriendlyExtension\Context\Helper\AbstractHelper;
use Symfony\Component\HttpKernel\KernelInterface;

class CommandHelper extends AbstractHelper
{
    private $loader;
    private $kernel;

    public function __construct(CommandLoader $loader, KernelInterface $kernel)
    {
        $this->loader = $loader;
        $this->kernel = $kernel;
    }

    public function getApplication()
    {
        $application = new Application($this->kernel);

        $this->loader->registerCommandsFromKernel($application, $this->kernel);

        return $application;
    }

    public function getCommands(Application $application)
    {
        $commands = $application->getCommands();
        ksort($commands);

        return $commands;
    }

    public function buildCommand(Application $application, $name)
    {
        $this
            ->get('asserter')
            ->assertTrue(
                $application->hasCommand($name),
                sprintf(
                    'No command named "%s" found. "%s" available.',
                    $name,
                    implode('", "', array_keys($this->getCommands($application)))
                )
            )
        ;

        return new CommandTester($application->getCommand($name));
    }

    public function clear()
    {
    }

    public function getName()
    {
        return 'command';
    }
}
