<?php

namespace Knp\FriendlyContexts\Tester;

use Behat\Behat\Tester\StepTester as BaseTester;
use Behat\Gherkin\Node\AbstractNode;

class StepTester extends BaseTester
{
    public function visit(AbstractNode $step)
    {
        $afterEvent = $this->executeStep($step);

        return $afterEvent->getResult();
    }
}
