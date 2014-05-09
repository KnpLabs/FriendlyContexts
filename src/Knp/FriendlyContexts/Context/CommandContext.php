<?php

namespace Knp\FriendlyContexts\Context;

use Knp\FriendlyContexts\Command\Application;
use Symfony\Component\Console\Tester\CommandTester;

class CommandContext extends Context
{
    protected $application;
    protected $command;
    protected $input;

    /**
     * @BeforeScenario
     **/
    public function loadCommands()
    {
        $this->application = new Application($this->getKernel());
        $this->command = null;

        $loader = $this->getCommandLoader();

        if (null !== $kernel = $this->getKernel()) {
            $loader->registerCommandsFromKernel($this->application, $kernel);
        } else {
            $loader->registerDefaultCommands($this->application);
        }
    }

    /**
     * @Then /^show available commands$/
     */
    public function showCommands()
    {
        $commands = $this->application->getCommands();
        ksort($commands);

        foreach ($commands as $name => $command)
        {
            echo "$name\n";
        }
    }

    /**
     * @Given I prepare a ":name" command
     */
    public function buildCommand($name)
    {
        $this
            ->getAsserter()
            ->assertTrue(
                $this->application->hasCommand($name),
                sprintf(
                    'No command named "%s" found. "%s" available.',
                    $name,
                    implode('", "', array_keys($this->application->getCommands()))
                )
            )
        ;

        $this->command = new CommandTester($this->application->getCommand($name));
        $this->input = [];
    }

    /**
     * @Given I add the argument ":name" with value ":value"
     */
    public function addArgument($name, $value = null)
    {
        $this->isCommandBuilding();

        $this->input[$name] = $value;
    }

    /**
     * @Given I add the option ":name"
     * @Given I add the option ":name" with value ":value"
     */
    public function addOption($name, $value = null)
    {
        $this->isCommandBuilding();

        $name = $this->cleanOption($name);
        $this->input[sprintf('--%s', $name)] = $value;
    }

    /**
     * @Given I add the short option ":name"
     * @Given I add the short option ":name" with value ":value"
     */
    public function addShortOption($name, $value = null)
    {
        $this->isCommandBuilding();

        $name = $this->cleanOption($name);
        $this->input[sprintf('-%s', $name)] = $value;
    }

    /**
     * @When I run the command
     */
    public function runCommand()
    {
        $this->isCommandBuilding();

        $this->command->execute($this->input);
    }

    /**
     * @Then show command result
     */
    public function showResult()
    {
        $this->isCommandBuilding();

        echo $this->command->getDisplay();
    }

    protected function isCommandBuilding()
    {
        $this
            ->getAsserter()
            ->assertNotNull($this->command, 'No command in building')
        ;
    }

    protected function cleanOption($name)
    {
        $matches = [];

        if (preg_match('/(--|-)(?P<name>.*)/', $name, $matches)) {

            return $matches['name'];
        }

        return $name;
    }
}
