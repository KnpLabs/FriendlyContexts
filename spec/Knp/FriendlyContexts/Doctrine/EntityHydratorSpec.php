<?php

namespace spec\Knp\FriendlyContexts\Doctrine;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EntityHydratorSpec extends ObjectBehavior
{
    /**
     * @param Knp\FriendlyContexts\Tool\TextFormater $formater
     * @param Knp\FriendlyContexts\Guesser\GuesserManager $manager
     * @param Knp\FriendlyContexts\Doctrine\EntityResolver $resolver
     **/
    function let($formater, $manager, $resolver)
    {
        $this->beConstructedWith($formater, $manager, $resolver);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Doctrine\EntityHydrator');
    }
}
