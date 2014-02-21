<?php

namespace spec\Knp\FriendlyContexts\Context;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RawPageContextSpec extends ObjectBehavior
{
    /**
     * @param Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param Knp\FriendlyContexts\Page\Resolver\PageClassResolver     $pageResolver
     * @param Behat\Mink\Mink                                          $mink
     * @param Behat\Mink\Session                                       $session
     */
    function let($container, $pageResolver, $mink, $session)
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
