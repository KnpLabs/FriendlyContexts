<?php

namespace Knp\FriendlyContexts\Context;

use Knp\FriendlyContexts\Command\Application;

class CommandContext extends Context
{
    protected $application;

    /**
     * @BeforeScenario
     **/
    public function loadCommands()
    {
        $this->application = new Application($this->getKernel());

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
        echo "Commands : \n";
        foreach ($this->application->getCommands() as $name => $command)
        {
            echo "\t$name\n";
        }
    }
}
