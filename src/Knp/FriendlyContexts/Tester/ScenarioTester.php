<?php

namespace Knp\FriendlyContexts\Tester;

use Behat\Behat\Tester\ScenarioTester as BaseTester;
use Behat\Gherkin\Node\AbstractNode;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ScenarioTester extends BaseTester
{
    private $context;

    public function __construct(ContainerInterface $container)
    {
        $this->context = $container->get('behat.context.dispatcher')->createContext();

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
}
