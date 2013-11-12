<?php

namespace spec\Knp\FriendlyContexts\Guesser;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GuesserManagerSpec extends ObjectBehavior
{

    /**
     * @param Knp\FriendlyContexts\Guesser\GuesserInterface $guesser
     **/
    function let($guesser)
    {
        $guesser->supports(Argument::any())->willReturn(false);
        $guesser->setManager($this)->willReturn(null);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Guesser\GuesserManager');
    }

    function it_should_return_false_when_no_guesser_supports_the_mapping()

    {
        $mapping = [
            'fieldName'  => "active",
            'type'       => "invalid",
            'scale'      => 0,
            'length'     => null,
            'unique'     => false,
            'nullable'   => false,
            'precision'  => 0,
            'columnName' => "active",
        ];

        $this->find($mapping)->shouldReturn(false);
    }

    function it_should_add_and_return_a_new_guesser($guesser)
    {
        $mapping = [
            'fieldName'  => "active",
            'type'       => "the_type",
            'scale'      => 0,
            'length'     => null,
            'unique'     => false,
            'nullable'   => false,
            'precision'  => 0,
            'columnName' => "active",
        ];

        $guesser->supports($mapping)->willReturn(true);

        $this->find($mapping)->shouldReturn(false);
        $this->addGuesser($guesser);
        $this->find($mapping)->shouldReturn($guesser);
    }

    function it_should_return_false_when_there_is_no_guesser()
    {
        $mapping = [
            'fieldName'  => "active",
            'type'       => "the_type",
            'scale'      => 0,
            'length'     => null,
            'unique'     => false,
            'nullable'   => false,
            'precision'  => 0,
            'columnName' => "active",
        ];

        $this->find($mapping)->shouldReturn(false);
    }
}
