<?php

namespace spec\Knp\FriendlyContexts\Guesser;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class IntGuesserSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Guesser\IntGuesser');
    }

    function it_should_support_smallint_mapping()
    {
        $mapping = [
            'fieldName'  => "number",
            'type'       => "integer",
            'scale'      => 0,
            'length'     => null,
            'unique'     => false,
            'nullable'   => false,
            'precision'  => 0,
            'columnName' => "number",
        ];

        $this->supports($mapping)->shouldReturn(true);
    }

    function it_should_support_other_mapping()
    {
        $mapping = [
            'fieldName'  => "created_at",
            'type'       => "datetime",
            'scale'      => 0,
            'length'     => null,
            'unique'     => false,
            'nullable'   => false,
            'precision'  => 0,
            'columnName' => "created_at",
        ];

        $this->supports($mapping)->shouldReturn(false);
    }

    function it_should_transform_string_to_int()
    {
        $this->transform("2")->shouldReturn(2);
        $this->transform("2.6")->shouldReturn(3);
        $this->transform(" ")->shouldReturn(0);
    }
}
