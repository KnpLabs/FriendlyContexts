<?php

namespace spec\Knp\FriendlyContexts\Context;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SmartContextSpec extends ObjectBehavior
{
    /**
     * @param Behat\Gherkin\Node\ScenarioNode $scenario
     * @param Behat\Gherkin\Node\StepNode $step1
     * @param Behat\Gherkin\Node\StepNode $step2
     * @param Behat\Gherkin\Node\StepNode $step3
     **/
    function let($scenario, $step1, $step2, $step3)
    {
        $scenario->getSteps()->willReturn([$step1, $step2, $step3]);

        $step1->getText()->willReturn('Step1');
        $step2->getText()->willReturn('Step2');
        $step3->getText()->willReturn('Step3');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Context\SmartContext');
    }

    function it_should_return_scenario_steps($scenario)
    {
        $steps = [
            'Step1',
            'Step2',
            'Step3',
        ];

        $this->executeScenario($scenario)->shouldReturn($steps);
    }
}
