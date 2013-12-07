<?php

namespace spec\Knp\FriendlyContexts\Tester;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ScenarioTesterSpec extends ObjectBehavior
{
    /**
     * @param Behat\Behat\Context\ContextDispatcher $dispatcher
     * @param Knp\FriendlyContexts\Tester\StepTester $tester
     * @param Symfony\Component\DependencyInjection\ContainerInterface $container
     **/
    function let($dispatcher, $tester, $container)
    {
        $container->get('behat.context.dispatcher')->willReturn($dispatcher);

        $this->beConstructedWith($container, $dispatcher, $tester);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Tester\ScenarioTester');
    }
}
