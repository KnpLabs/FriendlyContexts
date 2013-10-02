<?php

namespace spec\Knp\FriendlyContexts\Guesser;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DatetimeGuesserSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Guesser\DatetimeGuesser');
        $this->shouldHaveType('Knp\FriendlyContexts\Guesser\GuesserInterface');
    }

    function it_should_support_datetime_mapping_metadata()
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

        $this->supportMapping($mapping)->shouldReturn(true);
    }

    function it_should_not_support_non_datetime_mapping_metadata()
    {
        $mapping = [
            'fieldName'  => "created_by",
            'type'       => "string",
            'scale'      => 0,
            'length'     => null,
            'unique'     => false,
            'nullable'   => false,
            'precision'  => 0,
            'columnName' => "created_by",
        ];

        $this->supportMapping($mapping)->shouldReturn(false);
    }
}
