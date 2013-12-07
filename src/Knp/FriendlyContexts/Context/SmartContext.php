<?php

namespace Knp\FriendlyContexts\Context;

use Behat\Gherkin\Node\ScenarioNode;

class SmartContext extends Context
{
    public function executeScenario(ScenarioNode $scenario)
    {
        $tester = $this->get('friendly.tester.scenario');

        $tester->visit($scenario);
    }
}
