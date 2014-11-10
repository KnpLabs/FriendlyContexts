<?php

namespace spec\Knp\FriendlyExtension\Gherkin;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TagFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyExtension\Gherkin\TagFactory');
    }
}
