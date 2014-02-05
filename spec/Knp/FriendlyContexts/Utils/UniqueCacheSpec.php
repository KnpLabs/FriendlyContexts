<?php

namespace spec\Knp\FriendlyContexts\Utils;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UniqueCacheSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Utils\UniqueCache');
    }

    function its_generate_should_generate_a_new_unique_value()
    {
        $this->generate('Class', 'property', function () {
            return 'test';
        });

        $this->exists('Class', 'property', 'test')->shouldReturn(true);

        $cache = null;
        $this->generate('Class', 'property', function () use (&$cache) {
            $cache = rand();
            return $cache;
        });

        $this->exists('Class', 'property', $cache)->shouldReturn(true);
    }
}
