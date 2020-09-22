<?php

namespace spec\Knp\FriendlyContexts\Page\Resolver;

use Behat\Mink\Session;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PageClassResolverSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('Page\Namespace');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Page\Resolver\PageClassResolver');
    }

    function it_test_if_a_class_exists()
    {
        $this->exists('Behat\Mink\Mink')->shouldReturn(true);
        $this->exists('Invalid\Class')->shouldReturn(false);
    }

    function it_throw_an_exception_when_create_a_non_existent_page(Session $session)
    {
        $this->shouldThrow('InvalidArgumentException')->duringCreate($session, 'Invalid\Class');
    }

    function it_throw_an_exception_when_create_an_invalid_page(Session $session)
    {
        $this->shouldThrow('InvalidArgumentException')->duringCreate($session, 'Knp\FriendlyContexts\Page\Resolver\PageClassResolver');
    }

    function it_create_a_page_object(Session $session)
    {
        $this
            ->create($session, 'Knp\FriendlyContexts\Page\Page')
            ->shouldHaveType('Knp\FriendlyContexts\Page\Page')
        ;
    }

    function it_resolve_a_page_class_name()
    {
        $this->resolveName('some name')->shouldReturn('Page\Namespace\SomeNamePage');
    }
}
