<?php

namespace spec\Knp\FriendlyExtension\Utils;

use Knp\FriendlyExtension\Utils\TextFormater;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NameProposerSpec extends ObjectBehavior
{
    function let(TextFormater $formater)
    {
        $this->beConstructedWith($formater);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyExtension\Utils\NameProposer');
    }

    function it_propose_a_set_of_names()
    {
        $this->buildProposals('the_test')->shoulwReturn([

        ]);
    }
}
