<?php

namespace spec\Knp\FriendlyExtension\Gherkin;

use Behat\Behat\EventDispatcher\Event\ScenarioLikeTested;
use Behat\Gherkin\Node\FeatureNode;
use Behat\Gherkin\Node\ScenarioNode;
use Knp\FriendlyExtension\Gherkin\Tag;
use Knp\FriendlyExtension\Gherkin\TagFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TagLoaderSpec extends ObjectBehavior
{
    function let(TagFactory $factory, Tag $tag1, Tag $tag2, Tag $tag3, FeatureNode $feature, ScenarioNode $scenario, ScenarioLikeTested $event)
    {
        $this->beConstructedWith($factory);

        $event->getFeature()->willReturn($feature);
        $event->getScenario()->willReturn($scenario);

        $feature->getTags()->willReturn([ 'tag1', 'tag2', 'tag3(Foo)', 'tag3(Bar)' ]);
        $scenario->getTags()->willReturn([ '~tag2', 'tag3(~Foo)', 'tag3(Baz)' ]);

        $factory->create('tag1')->willReturn($tag1)->shouldBeCalledTimes(1);
        $factory->create('tag2')->willReturn($tag2)->shouldBeCalledTimes(1);
        $factory->create('tag3')->willReturn($tag3)->shouldBeCalledTimes(1);
    }

    function it_is_initializable(TagFactory $factory)
    {
        $factory->create('tag1')->shouldNotBeCalled();
        $factory->create('tag2')->shouldNotBeCalled();
        $factory->create('tag3')->shouldNotBeCalled();
        $this->shouldHaveType('Knp\FriendlyExtension\Gherkin\TagLoader');
    }

    function it_load_tags(ScenarioLikeTested $event, Tag $tag1, Tag $tag2, Tag $tag3)
    {
        $tag1->addArgument(Argument::cetera())->shouldNotBeCalled();
        $tag1->enable()->shouldBeCalled();
        $tag1->disable()->shouldNotBeCalled();

        $tag2->addArgument(Argument::cetera())->shouldNotBeCalled();
        $tag2->enable()->shouldBeCalled();
        $tag2->disable()->shouldBeCalled();

        $tag3->addArgument('Foo', true)->shouldBeCalled();
        $tag3->addArgument('Foo', false)->shouldBeCalled();
        $tag3->addArgument('Bar', true)->shouldBeCalled();
        $tag3->addArgument('Baz', true)->shouldBeCalled();
        $tag3->enable()->shouldBeCalled();
        $tag3->disable()->shouldNotBeCalled();

        $this->beforeScenario($event);
    }

    function it_return_a_tag_by_name(ScenarioLikeTested $event, Tag $tag1)
    {
        $this->beforeScenario($event);

        $this->getTag('tag1')->shouldReturn($tag1);
    }

    function it_return_null_if_tag_doesnt_exists(ScenarioLikeTested $event)
    {
        $this->beforeScenario($event);

        $this->getTag('tag9')->shouldBeNull();
    }
}
