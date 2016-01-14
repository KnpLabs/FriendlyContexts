<?php

namespace spec\Knp\FriendlyContexts\Guesser;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SmallStringGuesserSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Guesser\SmallStringGuesser');
        $this->shouldHaveType('Knp\FriendlyContexts\Guesser\GuesserInterface');
    }

    function it_should_supports_limited_string_mapping_metadata()
    {
        $mapping = [
            'fieldName'  => "name",
            'type'       => "string",
            'scale'      => 0,
            'length'     => 3,
            'unique'     => false,
            'nullable'   => false,
            'precision'  => 0,
            'columnName' => "name",
        ];

        $this->supports($mapping)->shouldReturn(true);
    }

    function it_should_not_supports_unlimited_string_mapping_metadata()
    {
        $this->supports([
            'fieldName'  => "name",
            'type'       => "string",
            'scale'      => 0,
            'length'     => null,
            'unique'     => false,
            'nullable'   => false,
            'precision'  => 0,
            'columnName' => "name",
        ])->shouldReturn(false);
    }

    function it_shoult_not_transform_string_entry()
    {
        $this->transform('test')->shouldReturn('test');
    }
}
