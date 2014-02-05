<?php

namespace spec\Knp\FriendlyContexts\Doctrine;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EntityHydratorSpec extends ObjectBehavior
{
    /**
     * @param Knp\FriendlyContexts\Utils\TextFormater      $formater
     * @param Knp\FriendlyContexts\Guesser\GuesserManager  $manager
     * @param Knp\FriendlyContexts\Doctrine\EntityResolver $resolver
     * @param Knp\FriendlyContexts\Utils\UniqueCache       $uniqueCache
     **/
    function let($formater, $manager, $resolver, $uniqueCache)
    {
        $this->beConstructedWith($formater, $manager, $resolver, $uniqueCache);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Doctrine\EntityHydrator');
    }
}
