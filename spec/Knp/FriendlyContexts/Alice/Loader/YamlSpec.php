<?php

namespace spec\Knp\FriendlyContexts\Alice\Loader;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class YamlSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Alice\Loader\Yaml');
    }
}
