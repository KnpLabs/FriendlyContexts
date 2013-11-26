<?php

namespace spec\Knp\FriendlyContexts\Node;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FeatureWalkerSpec extends ObjectBehavior
{
    /**
     * @param Behat\Gherkin\Gherkin $gherkin
     * @param Behat\Gherkin\Node\FeatureNode $feature1
     * @param Behat\Gherkin\Node\FeatureNode $feature2
     * @param Behat\Gherkin\Node\ScenarioNode $scenario1
     * @param Behat\Gherkin\Node\ScenarioNode $scenario2
     * @param Behat\Gherkin\Node\ScenarioNode $scenario3
     **/
    function let($gherkin, $feature1, $feature2, $scenario1, $scenario2, $scenario3)
    {
        $this->beConstructedWith($gherkin, 'paths');

        $gherkin->load('paths')->willReturn([$feature1, $feature2]);
        $feature1->getScenarios()->willReturn([$scenario1, $scenario3]);
        $feature2->getScenarios()->willReturn([$scenario2]);
        $scenario1->getTitle()->willReturn('Scenario1');
        $scenario2->getTitle()->willReturn('Scenario2');
        $scenario3->getTitle()->willReturn('Scenario3');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Node\FeatureWalker');
    }

    function it_should_return_scenario_by_name($scenario1, $scenario3)
    {
        $this->getScenarioByName('Scenario1')->shouldReturn($scenario1);
        $this->getScenarioByName('Scenario3')->shouldReturn($scenario3);
    }

    function it_should_return_unkown_scenario()
    {
        $this->getScenarioByName('Scenario4')->shouldReturn(null);
    }

    function it_should_return_all_scenario($scenario1, $scenario2, $scenario3)
    {
        $this->getScenarios()->shouldReturn([$scenario1, $scenario3, $scenario2]);
    }

    function it_should_return_all_features($feature1, $feature2)
    {
        $this->getFeatures()->shouldReturn([$feature1, $feature2]);
    }
}
