<?php

namespace Knp\FriendlyExtension\Context;

use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Knp\FriendlyExtension\Context\Context;

class CommandContext extends Context
{
    protected $application;
    protected $command;
    protected $input = [];

    /**
     * @BeforeScenario
     */
    public function clear()
    {
        $this->command = null;
        $this->input   = [];
    }

    /**
     * @Given I prepare a :name command
     */
    public function buildCommand($name)
    {
        $this->clear();
        $this->command = $this->get('command')->buildCommand($this->getApplication(), $name);
    }

    /**
     * @Given show available commands
     */
    public function showCommands()
    {
        foreach ($this->get('command')->getCommands($this->getApplication()) as $name => $command)
        {
            echo "$name\n";
        }
    }

    /**
     * @Given I add the argument :name with value ":value"
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
     * @Given I add the following command parameters:
     */
    public function andTheFollowing(TableNode $arguments)
    {
        $arguments = $arguments->getRowsHash();

        $this->input = array_merge($this->input, $arguments);
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

    /**
     * @Then the command result should contains :string
     */
    public function resultContainsString($string)
    {
        $expected = $string;
        $real = explode("\n", $this->command->getDisplay());

        $this
            ->get('asserter')
            ->assertArrayContains($expected, $real)
        ;
    }

    /**
     * @Then the command result should contains:
     */
    public function resultContainsStrings(PyStringNode $strings)
    {
        $expected = $strings->getStrings();
        $real = explode("\n", $this->command->getDisplay());

        $this
            ->get('asserter')
            ->assertArrayContains($expected, $real)
        ;
    }

    /**
     * @Then the command should be a success
     */
    public function isSuccess()
    {
        $this->testResult(0);
    }


    /**
     * @Then the command should be in error
     */
    public function isError()
    {
        $this->testResult(1);
    }

    /**
     * @Then the command result should be :code
     */
    public function testResult($code)
    {
        $this->isCommandBuilding();

        $this
            ->get('asserter')
            ->assertEquals((int)$code, $this->command->getStatusCode())
        ;
    }

    private function isCommandBuilding()
    {
        $this
            ->get('asserter')
            ->assertNotNull($this->command, 'No command in building')
        ;
    }

    private function getApplication()
    {
        return $this->application = $this->application
            ?: $this->get('command')->getApplication()
        ;
    }

    private function cleanOption($name)
    {
        $matches = [];

        if (preg_match('/(--|-)(?P<name>.*)/', $name, $matches)) {

            return $matches['name'];
        }

        return $name;
    }
}
