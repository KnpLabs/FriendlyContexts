<?php

namespace spec\Knp\FriendlyExtension\Gherkin;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TagSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('tag1');

        $this->addArgument('Arg1', true);
        $this->addArgument('Arg2', true);
        $this->addArgument('Arg3', false);
        $this->addArgument('Arg4', true);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyExtension\Gherkin\Tag');
    }

    function it_return_active_arguments()
    {
        $this->getArguments()->shouldReturn([ 'Arg1', 'Arg2', 'Arg4' ]);
    }

    function it_revoke_unactive_argument()
    {
        $this->revokeArgument('Arg3')->shouldReturn(true);
    }

    function it_doesnt_revoke_active_argument()
    {
        $this->revokeArgument('Arg2')->shouldReturn(false);
    }

    function it_doesnt_revoke_newly_unactive_argument()
    {
        $this->addArgument('Arg2', false);
        $this->revokeArgument('Arg2')->shouldReturn(true);
    }
}
