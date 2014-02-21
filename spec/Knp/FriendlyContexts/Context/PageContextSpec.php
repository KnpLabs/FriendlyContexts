<?php

namespace spec\Knp\FriendlyContexts\Context;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PageContextSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Context\PageContext');
    }

    function it_is_a_raw_page_context()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Context\RawPageContext');
    }
}
