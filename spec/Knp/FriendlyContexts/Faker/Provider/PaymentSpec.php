<?php

namespace spec\Knp\FriendlyContexts\Faker\Provider;

use Faker\Generator;
use Faker\Provider\Payment;
use Faker\Provider\Person;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PaymentSpec extends ObjectBehavior
{
    function let(Generator $generator, Payment $payment)
    {
        $this->beConstructedWith($generator);
        $this->setParent($payment);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Faker\Provider\Payment');
    }

    function it_should_return_parent_provider($payment)
    {
        $this->getParent()->shouldReturn($payment);
    }

    function it_should_supports_Payment_original_provider($payment)
    {
        $this->supportsParent($payment)->shouldReturn(true);
    }

    function it_should_not_supports_non_Payment_original_provider(Person $person)
    {
        $this->supportsParent($person)->shouldReturn(false);
    }
}
