<?php

namespace spec\Knp\FriendlyContexts\Guesser;

use PhpSpec\ObjectBehavior;

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

        $this->supports($mapping)->shouldReturn(true);
    }

    function it_should_support_date_mapping_metadata()
    {
        $mapping = [
            'fieldName'  => "created_at",
            'type'       => "date",
            'scale'      => 0,
            'length'     => null,
            'unique'     => false,
            'nullable'   => false,
            'precision'  => 0,
            'columnName' => "created_at",
        ];

        $this->supports($mapping)->shouldReturn(true);
    }

    function it_should_support_time_mapping_metadata()
    {
        $mapping = [
            'fieldName'  => "created_at",
            'type'       => "time",
            'scale'      => 0,
            'length'     => null,
            'unique'     => false,
            'nullable'   => false,
            'precision'  => 0,
            'columnName' => "created_at",
        ];

        $this->supports($mapping)->shouldReturn(true);
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

        $this->supports($mapping)->shouldReturn(false);
    }

    function it_should_generate_a_datetime_from_a_string()
    {
        $this->transform('now')->shouldHaveType("DateTime");
        $this->transform('today')->shouldHaveType("DateTime");
        $this->transform('+1 day')->shouldHaveType("DateTime");
        $this->transform('2013-10-01')->shouldHaveType("DateTime");
        $this->transform('2013-10-01 12:00')->shouldHaveType("DateTime");
        $this->transform('01-02-2010')->shouldHaveType("DateTime");
    }

    function it_should_fail_to_create_a_datetime_from_a_wrong_string()
    {
        $this->shouldThrow(new \Exception('"TEST" is not a supported date/time/datetime format. To know which formats are supported, please visit http://www.php.net/manual/en/datetime.formats.php'))->duringTransform('TEST');
    }
}
