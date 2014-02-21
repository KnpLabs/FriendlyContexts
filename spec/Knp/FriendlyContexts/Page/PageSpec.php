<?php

namespace spec\Knp\FriendlyContexts\Page;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PageSpec extends ObjectBehavior
{
    /**
     * @param Behat\Mink\Session $session
     */
    function let($session)
    {
        $this->beConstructedWith($session);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Page\Page');
    }

    function it_is_a_document_element()
    {
        $this->shouldHaveType('Behat\Mink\Element\DocumentElement');
    }

    function it_contains_the_page_path()
    {
        $this->path = 'foo/bar';

        $this->getPath()->shouldReturn('foo/bar');
    }
}
