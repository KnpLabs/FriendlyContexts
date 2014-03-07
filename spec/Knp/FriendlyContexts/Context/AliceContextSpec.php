<?php

namespace spec\Knp\FriendlyContexts\Context;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AliceContextSpec extends ObjectBehavior
{
    /**
     * @param Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param Doctrine\Common\Persistence\ManagerRegistry $doctrine
     * @param Doctrine\Common\Persistence\ObjectManager $manager
     * @param Behat\Behat\Tester\Event\ScenarioTested $event
     * @param Behat\Gherkin\Node\FeatureNode $feature
     * @param Behat\Gherkin\Node\ScenarioNode $scenario
     * @param Knp\FriendlyContexts\Alice\Loader\Yaml $loader
     **/
    function let($container, $doctrine, $manager, $event, $loader, $feature, $scenario)
    {
        $doctrine->getManager()->willReturn($manager);
        $feature->getTags()->willReturn([ 'alice(Place)', 'admin' ]);
        $scenario->getTags()->willReturn([ 'alice(User)' ]);
        $event->getFeature()->willReturn($feature);
        $event->getScenario()->willReturn($scenario);
        $loader->load('user.yml')->willReturn([]);
        $loader->load('product.yml')->willReturn([]);
        $loader->load('place.yml')->willReturn([]);
        $loader->getCache()->willReturn([]);
        $loader->clearCache()->willReturn(null);
        $fixtures = [ 'User' => 'user.yml', 'Product' => 'product.yml', 'Place' => 'place.yml' ];
        $config = [ 'alice' => [ 'fixtures' => $fixtures, 'dependencies' => [] ]];
        $container->has(Argument::any())->willReturn(true);
        $container->hasParameter(Argument::any())->willReturn(true);
        $container->get('friendly.alice.loader.yaml')->willReturn($loader);
        $container->get('doctrine')->willReturn($doctrine);
        $container->getParameter('friendly.alice.fixtures')->willReturn($fixtures);
        $container->getParameter('friendly.alice.dependencies')->willReturn([]);

        $this->initialize($config, $container);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Context\AliceContext');
    }

    function it_should_load_specific_fixtures($event, $loader)
    {
        $loader->load('user.yml')->shouldBeCalled();
        $loader->load('place.yml')->shouldBeCalled();
        $loader->load('product.yml')->shouldNotBeCalled();

        $this->loadAlice($event);
    }

    function it_should_load_all_fixtures($loader, $event, $scenario)
    {
        $scenario->getTags()->willReturn([ 'alice(*)' ]);

        $loader->load('user.yml')->shouldBeCalled();
        $loader->load('place.yml')->shouldBeCalled();
        $loader->load('product.yml')->shouldBeCalled();

        $this->loadAlice($event);
    }

    function it_should_resolve_deps($container, $loader, $event, $scenario)
    {
        $scenario->getTags()->willReturn([]);

        $loader->load('user.yml')->shouldBeCalled();
        $loader->load('place.yml')->shouldBeCalled();
        $loader->load('product.yml')->shouldNotBeCalled();

        $deps = [ 'Place' => [ 'User' ] ];
        $container->getParameter('friendly.alice.dependencies')->willReturn($deps);

        $this->loadAlice($event);
    }

    function it_should_not_loop_infinitly($container, $loader, $event, $scenario)
    {

        $scenario->getTags()->willReturn([]);

        $loader->load('user.yml')->shouldBeCalled();
        $loader->load('place.yml')->shouldBeCalled();
        $loader->load('product.yml')->shouldNotBeCalled();

        $deps = [ 'Place' => [ 'User' ], 'User' => [ 'Place' ] ];
        $container->getParameter('friendly.alice.dependencies')->willReturn($deps);

        $this->loadAlice($event);
    }
}
