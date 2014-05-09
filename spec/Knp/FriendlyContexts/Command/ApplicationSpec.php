<?php

namespace spec\Knp\FriendlyContexts\Command;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Console\Command\Command;

class ApplicationSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Command\Application');
    }

    function it_add_commands(Command $command)
    {
        $command->getName()->willReturn('the:command');

        $this->add($command);

        $this->getCommand('the:command')->shouldReturn($command);
    }

    function it_add_commands_with_name(Command $command)
    {
        $command->getName()->willReturn('the:command');

        $this->add($command, 'the:other:command');

        $this->hasCommand('the:command')->shouldReturn(false);
        $this->hasCommand('the:other:command')->shouldReturn(true);
        $this->getCommand('the:other:command')->shouldReturn($command);
    }
}
