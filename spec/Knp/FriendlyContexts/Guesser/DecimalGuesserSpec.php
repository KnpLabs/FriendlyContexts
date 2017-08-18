<?php

namespace spec\Knp\FriendlyContexts\Guesser;

use Knp\FriendlyContexts\Faker\Provider\Base;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DecimalGuesserSpec extends ObjectBehavior
{
    function let(Base $faker)
    {
        $faker->getParent()->willReturn('parent');
        $this->addFaker($faker);
    }

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

    function it_should_use_precision_and_scale_parameter_while_faking_a_decimal(Base $faker)
    {
        $mapping = [
            'scale'     => 3,
            'precision' => 5,
        ];

        $faker->fake('randomFloat', [3, 0, 99.999])->shouldBeCalled();

        $this->fake($mapping);
    }

    function it_should_use_only_min_limit_of_0_if_precision_and_scale_parameters_are_not_provided(Base $faker)
    {
        $faker->fake('randomFloat', [null, 0, null])->shouldBeCalled();

        $this->fake([]);
    }

    function it_should_use_precision_parameter_to_determine_max_decimal_value(Base $faker)
    {
        $mapping = [
            'precision' => 5,
        ];

        $faker->fake('randomFloat', [0, 0, 99999])->shouldBeCalled();

        $this->fake($mapping);
    }

    function it_should_use_scale_parameter_to_generate_decimal_number(Base $faker)
    {
        $mapping = [
            'scale' => 3,
        ];

        $faker->fake('randomFloat', [3, 0, 0.999])->shouldBeCalled();

        $this->fake($mapping);
    }

    function it_should_fake_decimal_number_if_scale_equals_precision(Base $faker)
    {
        $mapping = [
            'scale'     => 5,
            'precision' => 5,
        ];

        $faker->fake('randomFloat', [5, 0, 0.99999])->shouldBeCalled();

        $this->fake($mapping);
    }
}
