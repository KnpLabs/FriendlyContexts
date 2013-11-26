<?php

namespace spec\Knp\FriendlyContexts\Loader;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ScenarioLoaderSpec extends ObjectBehavior
{
    /**
     * @param Behat\Behat\Definition\DefinitionDispatcher $definitionDispatcher
     * @param Knp\FriendlyContexts\Node\FeatureWalker $featureWalker
     * @param Knp\FriendlyContexts\Context\SmartContext $smart
     * @param Behat\Behat\Context\ContextInterface $context
     * @param Behat\Gherkin\Node\ScenarioNode $scenario
     **/
    function let($definitionDispatcher, $featureWalker, $smart, $context, $scenario)
    {
        $this->beConstructedWith($definitionDispatcher, $featureWalker);

        $featureWalker->getScenarios()->willReturn([]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Loader\ScenarioLoader');
    }

    function it_should_supports_smart_context($smart)
    {
        $this->supports($smart)->shouldReturn(true);
    }

    function it_should_not_supports_other_contextx($context)
    {
        $this->supports($context)->shouldReturn(false);
    }

    function it_should_load_any_definition($definitionDispatcher, $scenario, $smart)
    {
        $definitionDispatcher->addDefinition(Argument::any())->shouldNotBeCalled();

        $this->load($smart)->shouldReturn(null);
    }

    function it_should_load_definitions($featureWalker, $definitionDispatcher, $scenario, $smart)
    {
        $definitionDispatcher->addDefinition(Argument::any())->shouldBeCalled();
        $featureWalker->getScenarios()->willReturn([$scenario]);

        $this->load($smart)->shouldReturn(null);
    }
}
