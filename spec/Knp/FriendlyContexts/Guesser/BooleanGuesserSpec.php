<?php

namespace spec\Knp\FriendlyContexts\Guesser;

use PhpSpec\ObjectBehavior;

class BooleanGuesserSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Guesser\BooleanGuesser');
        $this->shouldHaveType('Knp\FriendlyContexts\Guesser\GuesserInterface');
    }

    function it_should_support_boolean_mapping_metadata()
    {
        $mapping = [
            'fieldName'  => "active",
            'type'       => "boolean",
            'scale'      => 0,
            'length'     => null,
            'unique'     => false,
            'nullable'   => false,
            'precision'  => 0,
            'columnName' => "active",
        ];

        $this->supports($mapping)->shouldReturn(true);
    }

    function it_should_not_support_non_boolean_mapping_metadata()
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

    function it_should_generate_a_boolean_from_a_string()
    {
        $this->transform('true')->shouldReturn(true);
        $this->transform('no')->shouldReturn(false);
    }

    function it_should_fail_to_create_a_boolean_from_a_wrong_string()
    {
        $this->shouldThrow(new \Exception('"test" is not a supported format. Supported format : [active, activated, enabled, disabled, true, false, yes, no, 1, 0].'))->duringTransform('TEST');
    }
}
