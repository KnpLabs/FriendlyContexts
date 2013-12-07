<?php

namespace Knp\FriendlyContexts\Tester;

use Behat\Behat\Context\ContextDispatcher;
use Behat\Behat\Context\ContextInterface;
use Behat\Behat\Tester\ScenarioTester as BaseTester;
use Behat\Gherkin\Node\AbstractNode;
use Behat\Gherkin\Node\StepNode;
use Behat\Gherkin\Node\ScenarioNode;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ScenarioTester extends BaseTester
{
    private $context;

    public function __construct(ContainerInterface $container, ContextDispatcher $dispatcher, StepTester $stepTester)
    {
        $this->context    = $dispatcher->createContext();
        $this->stepTester = $stepTester;

        parent::__construct($container);
    }

    public function visit(AbstractNode $scenario)
    {
        $result = 0;
        $skip   = false;

        foreach ($scenario->getSteps() as $step) {
            $stResult = $this->visitStep($step, $scenario, $this->context, array(), $skip);
            if (0 !== $stResult) {
                $skip = true;
            }
            $result = max($result, $stResult);
        }

        return $result;
    }

    protected function visitStep(StepNode $step, ScenarioNode $logicalParent, ContextInterface $context, array $tokens = array(), $skip = false)
    {
        $this->stepTester->setLogicalParent($logicalParent);
        $this->stepTester->setContext($context);
        $this->stepTester->skip($skip);

        return $step->accept($this->stepTester);
    }
}
