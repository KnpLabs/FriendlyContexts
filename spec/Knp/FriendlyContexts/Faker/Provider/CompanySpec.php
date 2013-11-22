<?php

namespace spec\Knp\FriendlyContexts\Faker\Provider;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CompanySpec extends ObjectBehavior
{
    /**
     * @param Faker\Generator $generator
     * @param Faker\Provider\Company $company
     **/
    function let($generator, $company)
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

    /**
     * @param Faker\Provider\Person $person
     **/
    function it_should_not_supports_non_Company_original_provider($person)
    {
        $this->supportsParent($person)->shouldReturn(false);
    }
}
