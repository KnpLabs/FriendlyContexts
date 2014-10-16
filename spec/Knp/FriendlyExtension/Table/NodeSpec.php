<?php

namespace spec\Knp\FriendlyExtension\Table;

use Knp\FriendlyExtension\Table\Node;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NodeSpec extends ObjectBehavior
{
    function let(Node $right, Node $bottom, Node $other, Node $otherRight, Node $otherBottom)
    {
        $this->setContent('this');
        $this->setRight($right);
        $this->setBottom($bottom);

        $other->getContent()->willReturn('this');
        $other->getRight()->willReturn($otherRight);
        $other->getBottom()->willReturn($otherBottom);

        $right->equals($otherRight)->willReturn(true);
        $bottom->equals($otherBottom)->willReturn(true);

        $other->equals(Argument::any())->shouldNotBeCalled();
        $otherRight->equals(Argument::any())->shouldNotBeCalled();
        $otherBottom->equals(Argument::any())->shouldNotBeCalled();
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyExtension\Table\Node');
    }

    function it_is_equals(Node $other)
    {
        $this->equals($other)->shouldReturn(true);
    }

    function it_is_not_equals_if_content_is_different(Node $other)
    {
        $other->getContent()->willReturn('other');
        $this->equals($other)->shouldReturn(false);
    }

    function it_is_not_equals_if_branch_are_diferent(Node $other, Node $right, Node $otherRight)
    {
        $right->equals($otherRight)->willReturn(false);
        $this->equals($other)->shouldReturn(false);
    }

    function it_is_not_equals_if_branch_are_inexistant(Node $other)
    {
        $other->getRight()->willReturn(null);
        $this->equals($other)->shouldReturn(false);
    }

    function it_is_equals_if_branch_are_inexistant_but_the_other_is_partial(Node $other)
    {
        $other->getRight()->willReturn(null);
        $this->equals($other, true)->shouldReturn(true);
    }
}
