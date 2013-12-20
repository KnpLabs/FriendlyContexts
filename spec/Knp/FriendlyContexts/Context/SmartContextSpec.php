<?php

namespace spec\Knp\FriendlyContexts\Context;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SmartContextSpec extends ObjectBehavior
{
    /**
     * @param Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param Behat\Gherkin\Node\ScenarioNode $scenario
     * @param Knp\FriendlyContexts\Tester\ScenarioTester $tester
     **/
    function let($container, $scenario, $tester)
    {
        $container->has(Argument::any())->willReturn(true);
        $container->get('friendly.tester.scenario')->willReturn($tester);

        $this->initialize([], $container);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Context\SmartContext');
    }

    function it_should_return_scenario_steps($scenario, $tester)
    {
        $tester->visit($scenario)->shouldBeCalled();

        $this->executeScenario($scenario)->shouldReturn(null);
    }
}
