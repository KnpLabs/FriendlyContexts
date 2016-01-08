<?php

namespace Knp\FriendlyContexts\Context;

use Behat\Gherkin\Node\PyStringNode;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CommandContext extends Context
{
    /**
     * @var StreamOutput
     */
    private $output;

    /**
     * @var int
     */
    private $exitCode;

    /**
     * @var \Exception
     */
    private $exception;

    /**
     * @var Application
     */
    private $application;

    /**
     * {@inheritdoc}
     *
     * @throws \LogicException You must install symfony/framework-bundle dependency to use CommandContext.
     */
    public function initialize(array $config, ContainerInterface $container)
    {
        parent::initialize($config, $container);

        // Prepare Application class to run Symfony commands
        if (!class_exists('Symfony\Bundle\FrameworkBundle\Console\Application')) {
            throw new \LogicException('You must install symfony/framework-bundle dependency to use CommandContext.');
        }
        $this->application = new Application($this->getKernel());
    }

    /**
     * @param string $command
     *
     * @When /^I run (.*)$/
     */
    public function iRunCommand($command)
    {
        $inputString = trim($command);
        $input = new StringInput($inputString);
        $this->output = new StreamOutput(tmpfile());
        $this->exception = null;

        try {
            $this->exitCode = $this->application->doRun($input, $this->output);
        } catch (\Exception $e) {
            $this->exception = $e;
            $this->exitCode = -255;
        }
    }

    /**
     * @param int $code
     *
     * @throws \Exception
     *
     * @Then /^command should be successfully executed$/
     * @Then /^command exit code should be (?P<code>\-\d+|\d+)$/
     */
    public function commandExitCodeShouldBe($code = 0)
    {
        try {
            \PHPUnit_Framework_Assert::assertEquals($code, $this->exitCode);
        } catch (\PHPUnit_Framework_ExpectationFailedException $e) {
            throw new \Exception(
                sprintf('Command exit code "%s" does not match expected "%s"', $this->exitCode, $code),
                0,
                $e
            );
        }
    }

    /**
     * @param PyStringNode $message
     *
     * @throws \Exception
     *
     * @Then /^command should throw an exception$/
     * @Then /^command should throw following exception:?$/
     */
    public function commandShouldThrowException(PyStringNode $message = null)
    {
        if (!$this->exception instanceof \Exception) {
            throw new \Exception('Command does not throw any exception', 0, $this->exception);
        }
        if (null !== $message) {
            try {
                \PHPUnit_Framework_Assert::assertSame($message->getRaw(), $this->exception->getMessage());
            } catch (\PHPUnit_Framework_ExpectationFailedException $e) {
                throw new \Exception(
                    sprintf(
                        'Command exception message "%s" does not match expected "%s"',
                        $this->exception->getMessage(),
                        $message->getRaw()
                    ),
                    0,
                    $e
                );
            }
        }
    }

    /**
     * @param PyStringNode $string
     *
     * @throws \Exception
     *
     * @Then /^command output should be like:?$/
     */
    public function commandOutputShouldBeLike(PyStringNode $string)
    {
        $commandOutput = $this->getRawCommandOutput();
        $pyStringNodeContent = $string->getRaw();

        try {
            \PHPUnit_Framework_Assert::assertContains($pyStringNodeContent, $commandOutput);
        } catch (\PHPUnit_Framework_ExpectationFailedException $e) {
            throw new \Exception(
                sprintf("Command output is not like it should be\n#########>\n%s\n<#########\n", $commandOutput),
                0,
                $e
            );
        }
    }

    /**
     * @return string
     *
     * @throws \Exception
     */
    private function getRawCommandOutput()
    {
        if (!$this->output) {
            throw new \Exception('No command output!');
        }
        rewind($this->output->getStream());

        return stream_get_contents($this->output->getStream());
    }
}
