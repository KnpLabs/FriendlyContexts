<?php

namespace spec\Knp\FriendlyContexts\Tool;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TextFormaterSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Tool\TextFormater');
    }

    function it_should_camel_case_string()
    {
        $this->toCamelCase('the first string')->shouldReturn('TheFirstString');
        $this->toCamelCase('string')->shouldReturn('String');
    }

    function it_should_underscore_string()
    {
        $this->toUnderscoreCase('the first string')->shouldReturn('the_first_string');
        $this->toUnderscoreCase('string')->shouldReturn('string');
    }
}
