<?php

namespace spec\Knp\FriendlyContexts\Context;

use Behat\Behat\Hook\Scope\ScenarioScope;
use Behat\Gherkin\Node\FeatureNode;
use Behat\Gherkin\Node\ScenarioNode;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\Common\Persistence\Mapping\ClassMetadataFactory;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\FriendlyContexts\Alice\Fixtures\Loader;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AliceContextSpec extends ObjectBehavior
{
    function let(
        ContainerInterface $container,
        ManagerRegistry $doctrine,
        ObjectManager $manager,
        ScenarioScope $event,
        Loader $loader,
        FeatureNode $feature,
        ScenarioNode $scenario,
        ClassMetadataFactory $metadataFactory,
        ClassMetadata $userMetadata,
        ClassMetadata $placeMetadata,
        ClassMetadata $productMetadata
    ) {
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
        $container->get('friendly.alice.fixtures.loader')->willReturn($loader);
        $container->get('doctrine')->willReturn($doctrine);
        $container->getParameter('friendly.alice.fixtures')->willReturn($fixtures);
        $container->getParameter('friendly.alice.dependencies')->willReturn([]);
        $manager->getMetadataFactory()->willReturn($metadataFactory);
        $metadataFactory->getAllMetadata()->willReturn([$userMetadata, $placeMetadata, $productMetadata]);
        $userMetadata->getName()->willReturn('User');
        $placeMetadata->getName()->willReturn('Place');
        $productMetadata->getName()->willReturn('Product');

        $this->initialize($config, $container);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Context\AliceContext');
    }

    function it_should_load_specific_fixtures($event, $loader, $manager)
    {
        $manager->flush()->shouldBeCalled();

        $loader->load('user.yml')->shouldBeCalled();
        $loader->load('place.yml')->shouldBeCalled();
        $loader->load('product.yml')->shouldNotBeCalled();

        $this->loadAlice($event);
    }

    function it_should_load_all_fixtures($loader, $event, $scenario, $manager)
    {
        $scenario->getTags()->willReturn([ 'alice(*)' ]);
        $manager->flush()->shouldBeCalled();

        $loader->load('user.yml')->shouldBeCalled();
        $loader->load('place.yml')->shouldBeCalled();
        $loader->load('product.yml')->shouldBeCalled();

        $this->loadAlice($event);
    }

    function it_should_resolve_deps($container, $loader, $event, $scenario, $manager)
    {
        $scenario->getTags()->willReturn([]);
        $manager->flush()->shouldBeCalled();

        $loader->load('user.yml')->shouldBeCalled();
        $loader->load('place.yml')->shouldBeCalled();
        $loader->load('product.yml')->shouldNotBeCalled();

        $deps = [ 'Place' => [ 'User' ] ];
        $container->getParameter('friendly.alice.dependencies')->willReturn($deps);

        $this->loadAlice($event);
    }

    function it_should_not_loop_infinitly($container, $loader, $event, $scenario, $manager)
    {

        $scenario->getTags()->willReturn([]);
        $manager->flush()->shouldBeCalled();

        $loader->load('user.yml')->shouldBeCalled();
        $loader->load('place.yml')->shouldBeCalled();
        $loader->load('product.yml')->shouldNotBeCalled();

        $deps = [ 'Place' => [ 'User' ], 'User' => [ 'Place' ] ];
        $container->getParameter('friendly.alice.dependencies')->willReturn($deps);

        $this->loadAlice($event);
    }
}
