<?php

namespace spec\Knp\FriendlyContexts\Context;

use Behat\Mink\Mink;
use Behat\Mink\Session;
use Knp\FriendlyContexts\Page\Resolver\PageClassResolver;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RawPageContextSpec extends ObjectBehavior
{
    function let(ContainerInterface $container, PageClassResolver $pageResolver, Mink $mink, Session $session)
    {
        $container->get('friendly.page.resolver')->willReturn($pageResolver);
        $container->has('friendly.page.resolver')->willReturn(true);

        $this->initialize([
            'page' => [
                'namespace' => 'Page\Namespace'
            ]
        ], $container);

        $mink->getSession(Argument::cetera())->willReturn($session);

        $this->setMink($mink);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Context\RawPageContext');
    }

    function it_is_a_mink_context()
    {
        $this->shouldHaveType('Behat\MinkExtension\Context\MinkAwareContext');
    }

    /**
     * @param Knp\FriendlyContexts\Page\Page $page
     */
    function it_retrieve_page($container, $pageResolver, $session, $page)
    {
        $container->get('friendly.page.resolver')->shouldBeCalled(1);
        $pageResolver
            ->create($session, 'Page\Namespace\SomePage')
            ->shouldBeCalled(1)
            ->willReturn($page)
        ;
        $pageResolver
            ->resolveName('some')
            ->shouldBeCalled(1)
            ->willReturn('Page\Namespace\SomePage')
        ;

        $this->getPage('some')->shouldReturn($page);
    }
}
