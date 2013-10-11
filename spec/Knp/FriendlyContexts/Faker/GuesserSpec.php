<?php

namespace spec\Knp\FriendlyContexts\Faker;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GuesserSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Faker\Guesser');
    }

    function it_shoult_return_a_provider()
    {
        $this->getProvider('Address')      ->shouldHaveType('Knp\FriendlyContexts\Faker\Provider\Address');
        $this->getProvider('Color')        ->shouldHaveType('Knp\FriendlyContexts\Faker\Provider\Color');
        $this->getProvider('Company')      ->shouldHaveType('Knp\FriendlyContexts\Faker\Provider\Company');
        $this->getProvider('DateTime')     ->shouldHaveType('Knp\FriendlyContexts\Faker\Provider\DateTime');
        $this->getProvider('File')         ->shouldHaveType('Knp\FriendlyContexts\Faker\Provider\File');
        $this->getProvider('Internet')     ->shouldHaveType('Knp\FriendlyContexts\Faker\Provider\Internet');
        $this->getProvider('Lorem')        ->shouldHaveType('Knp\FriendlyContexts\Faker\Provider\Lorem');
        $this->getProvider('Miscellaneous')->shouldHaveType('Knp\FriendlyContexts\Faker\Provider\Miscellaneous');
        $this->getProvider('Payment')      ->shouldHaveType('Knp\FriendlyContexts\Faker\Provider\Payment');
        $this->getProvider('Person')       ->shouldHaveType('Knp\FriendlyContexts\Faker\Provider\Person');
        $this->getProvider('PhoneNumber')  ->shouldHaveType('Knp\FriendlyContexts\Faker\Provider\PhoneNumber');
        $this->getProvider('UserAgent')    ->shouldHaveType('Knp\FriendlyContexts\Faker\Provider\UserAgent');
        $this->getProvider('Uuid')         ->shouldHaveType('Knp\FriendlyContexts\Faker\Provider\Uuid');
    }

    function it_should_throw_an_error_when_provider_is_unknown()
    {
        $this->shouldThrow(new \Exception('There is no provider named "test", "Address", "Color", "Company", "DateTime", "File", "Internet", "Lorem", "Miscellaneous", "Payment", "Person", "PhoneNumber", "UserAgent", "Uuid" availables'))->duringGetProvider('test');
    }
}
