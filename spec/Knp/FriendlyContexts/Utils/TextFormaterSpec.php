<?php

namespace spec\Knp\FriendlyContexts\Utils;

use PhpSpec\ObjectBehavior;

class TextFormaterSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Utils\TextFormater');
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

    function it_should_add_spaces_after_word()
    {
        $this->addSpaceAfter('test', 6)->shouldReturn('test  ');
        $this->addSpaceAfter('test', 2)->shouldReturn('test');
        $this->addSpaceAfter('test', 4)->shouldReturn('test');
    }
}
