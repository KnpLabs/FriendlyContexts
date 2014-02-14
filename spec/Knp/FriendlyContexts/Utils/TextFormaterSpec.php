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

    function it_should_build_array()
    {
        $this->tableToString([ 'test', 'tata', 'toto' ])->shouldReturn('| test | tata | toto |');
    }

    function it_should_build_table()
    {
        $table = [
            [ 'test', 'tata', 'toto' ],
            [ '0', '123456789', 'azertyuiop' ],
            [ '987654321', '0', '12345' ],
        ];

        $return = "| test      | tata      | toto       |\n| 0         | 123456789 | azertyuiop |\n| 987654321 | 0         | 12345      |\n";

        $this->tableToString($table)->shouldReturn($return);
    }
}
