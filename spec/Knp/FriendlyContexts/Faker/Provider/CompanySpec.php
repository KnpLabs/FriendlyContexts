<?php

namespace spec\Knp\FriendlyContexts\Faker\Provider;

use Faker\Generator;
use Faker\Provider\Company;
use Faker\Provider\Person;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CompanySpec extends ObjectBehavior
{
    function let(Generator $generator, Company $company)
    {
        $this->beConstructedWith($generator);
        $this->setParent($company);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Faker\Provider\Company');
    }

    function it_should_return_parent_provider($company)
    {
        $this->getParent()->shouldReturn($company);
    }

    function it_should_supports_Company_original_provider($company)
    {
        $this->supportsParent($company)->shouldReturn(true);
    }

    function it_should_not_supports_non_Company_original_provider(Person $person)
    {
        $this->supportsParent($person)->shouldReturn(false);
    }
}
