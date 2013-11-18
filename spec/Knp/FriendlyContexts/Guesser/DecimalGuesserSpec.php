<?php

namespace spec\Knp\FriendlyContexts\Guesser;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DecimalGuesserSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Guesser\DecimalGuesser');
    }

    function it_should_transform_a_string_to_float()
    {
        $this->transform('12.21')->shouldReturn(12.21);
    }

    function it_should_supports_decimal_mapping()
    {
        $mapping = [
            'fieldName'  => "pricing",
            'type'       => "decimal",
            'scale'      => 0,
            'length'     => null,
            'unique'     => false,
            'nullable'   => false,
            'precision'  => 0,
            'columnName' => "pricing",
        ];

        $this->supports($mapping)->shouldReturn(true);
    }

    function it_should_supports_float_mapping()
    {
        $mapping = [
            'fieldName'  => "pricing",
            'type'       => "float",
            'scale'      => 0,
            'length'     => null,
            'unique'     => false,
            'nullable'   => false,
            'precision'  => 0,
            'columnName' => "pricing",
        ];

        $this->supports($mapping)->shouldReturn(true);
    }

    function it_should_not_supports_other_mapping()
    {
        $mapping = [
            'fieldName'  => "pricing",
            'type'       => "int",
            'scale'      => 0,
            'length'     => null,
            'unique'     => false,
            'nullable'   => false,
            'precision'  => 0,
            'columnName' => "pricing",
        ];

        $this->supports($mapping)->shouldReturn(false);
    }
}
