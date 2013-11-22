<?php

namespace spec\Knp\FriendlyContexts\Faker\Provider;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PaymentSpec extends ObjectBehavior
{
    /**
     * @param Faker\Generator $generator
     * @param Faker\Provider\Payment $payment
     **/
    function let($generator, $payment)
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

    /**
     * @param Faker\Provider\Person $person
     **/
    function it_should_not_supports_non_Payment_original_provider($person)
    {
        $this->supportsParent($person)->shouldReturn(false);
    }
}
