<?php

namespace Knp\FriendlyContexts\Context;

use Behat\Gherkin\Node\ScenarioNode;

class SmartContext extends Context
{
    public function executeScenario(ScenarioNode $scenario)
    {
        $steps = [];

        foreach ($scenario->getSteps() as $step) {
            $steps[] = $step->getText();
        }

        return $steps;
    }
}
