<?php

namespace spec\Knp\FriendlyContexts\Guesser;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class StringGuesserSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Guesser\StringGuesser');
        $this->shouldHaveType('Knp\FriendlyContexts\Guesser\GuesserInterface');
    }

    function it_should_supports_string_mapping_metadata()
    {
        $mapping = [
            'fieldName'  => "name",
            'type'       => "string",
            'scale'      => 0,
            'length'     => null,
            'unique'     => false,
            'nullable'   => false,
            'precision'  => 0,
            'columnName' => "name",
        ];

        $this->supports($mapping)->shouldReturn(true);
    }

    function it_should_supports_text_mapping_metadata()
    {
        $mapping = [
            'fieldName'  => "conten",
            'type'       => "text",
            'scale'      => 0,
            'length'     => null,
            'unique'     => false,
            'nullable'   => false,
            'precision'  => 0,
            'columnName' => "content",
        ];

        $this->supports($mapping)->shouldReturn(true);
    }

    function it_shoult_not_transform_string_entry()
    {
        $this->transform('test')->shouldReturn('test');
    }
}
