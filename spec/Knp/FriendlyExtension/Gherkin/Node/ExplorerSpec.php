<?php

namespace spec\Knp\FriendlyExtension\Gherkin\Node;

use Behat\Gherkin\Node\FeatureNode;
use Behat\Gherkin\Node\ScenarioNode;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ExplorerSpec extends ObjectBehavior
{
    function let(FeatureNode $feature, ScenarioNode $scenario)
    {
        $feature->getTags()->willReturn([
            'javascript',
            'reset-schema',
            '~admin',
            'alice(Product)',
            'alice(User)',
        ]);
        $scenario->getTags()->willReturn([
            '~javascript',
            'admin',
            'alice(Prototype, Order)',
            '~alice(User)',
        ]);

        $this->beConstructedWith($feature);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyExtension\Gherkin\Node\Explorer');
    }

    function it_returns_feature_tags()
    {
        $this->getTags()->shouldReturn([ 'alice', 'javascript', 'reset-schema' ]);
    }

    function it_returns_scenario_tags(FeatureNode $feature, ScenarioNode $scenario)
    {
        $this->beConstructedWith($feature, $scenario);
        $this->getTags()->shouldReturn([ 'admin', 'alice', 'reset-schema' ]);
    }

    function it_return_arguments()
    {
        $this->getArguments('alice')->shouldReturn([ 'Product', 'User' ]);
    }

    function it_return_arguments_with_scenario(FeatureNode $feature, ScenarioNode $scenario)
    {
        $this->beConstructedWith($feature, $scenario);
        $this->getArguments('alice')->shouldReturn([ 'Order', 'Product', 'Prototype' ]);
    }
}
