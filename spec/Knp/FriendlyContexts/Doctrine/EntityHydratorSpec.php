<?php

namespace spec\Knp\FriendlyContexts\Doctrine;

use Knp\FriendlyContexts\Doctrine\EntityResolver;
use Knp\FriendlyContexts\Guesser\GuesserManager;
use Knp\FriendlyContexts\Utils\TextFormater;
use Knp\FriendlyContexts\Utils\UniqueCache;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EntityHydratorSpec extends ObjectBehavior
{
    function let(TextFormater $formater, GuesserManager $manager, EntityResolver $resolver, UniqueCache $uniqueCache)
    {
        $this->beConstructedWith($formater, $manager, $resolver, $uniqueCache);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Doctrine\EntityHydrator');
    }
}
