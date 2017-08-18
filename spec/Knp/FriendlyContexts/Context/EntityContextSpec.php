<?php

namespace spec\Knp\FriendlyContexts\Context;

use Behat\Gherkin\Node\TableNode;
use Doctrine\ORM\Mapping\ClassMetadata;
use Knp\FriendlyContexts\Doctrine\EntityResolver;
use Knp\FriendlyContexts\Utils\Asserter;
use Knp\FriendlyContexts\Utils\TextFormater;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EntityContextSpec extends ObjectBehavior
{
    /**
     * @param Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param Doctrine\Common\Persistence\ManagerRegistry $doctrine
     * @param Doctrine\Common\Persistence\ObjectManager $manager
     * @param Doctrine\ORM\EntityRepository $repository
     * @param Doctrine\ORM\QueryBuilder $queryBuilder
     * @param Doctrine\ORM\AbstractQuery $query
     * @param Knp\FriendlyContexts\Doctrine\EntityResolver $resolver
     * @param Knp\FriendlyContexts\Record\Collection\Bag $bag
     * @param Knp\FriendlyContexts\Record\Collection $collection
     * @param Knp\FriendlyContexts\Record\Record $record1
     * @param Knp\FriendlyContexts\Record\Record $record2
     * @param Knp\FriendlyContexts\Utils\Asserter $asserter
     * @param \ReflectionClass $reflectionClass
     **/
    function let($container, $doctrine, $manager, $repository, $queryBuilder, $query, $resolver, $bag, $collection, \ReflectionClass $reflectionClass, $asserter, $record1, $record2, $entity1, $entity2, $entity3)
    {
        $entity1 = 'e1';
        $entity2 = 'e2';
        $entity3 = 'e3';
        $doctrine->getManager()->willReturn($manager);
        $manager->getRepository(Argument::any())->willReturn($repository);
        $repository->createQueryBuilder(Argument::any())->willReturn($queryBuilder);
        $queryBuilder->getQuery()->willReturn($query);
        $queryBuilder->resetDQLParts()->willReturn($queryBuilder);
        $queryBuilder->select('o')->willReturn($queryBuilder);
        $queryBuilder->from(Argument::cetera())->willReturn($queryBuilder);
        $query->getResult()->willReturn([$entity1, $entity2, $entity3]);
        $resolver->resolve($manager, 'entities', Argument::cetera())->willReturn([$reflectionClass]);
        $bag->getCollection(Argument::any())->willReturn($collection);
        $collection->all()->willReturn([$record1, $record2]);
        $collection->attach(Argument::cetera())->willReturn(null);
        $record1->getEntity()->willReturn($entity1);
        $record2->getEntity()->willReturn($entity2);

        $container->has(Argument::any())->willReturn(true);
        $container->get('doctrine')->willReturn($doctrine);
        $container->get('friendly.entity.resolver')->willReturn($resolver);
        $container->get('friendly.entity.resolver')->willReturn($resolver);
        $container->get('friendly.record.bag')->willReturn($bag);
        $container->get('friendly.asserter')->willReturn(new Asserter(new TextFormater));
        $container->hasParameter('friendly.entities.namespaces')->willReturn(true);
        $container->getParameter('friendly.entities.namespaces')->willReturn([]);

        $this->initialize([], $container);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Context\EntityContext');
    }

    function it_should_assert_a_deletion()
    {
        $this->entitiesShouldHaveBeen('no', 'entities', 'deleted')->shouldReturn(null);
        $this->shouldThrow(new \Exception('2 entities should have been deleted, 0 actually', 1))->duringEntitiesShouldHaveBeen('2', 'entities', 'deleted');
    }

    function it_should_assert_a_creation($collection)
    {
        $collection->attach('e1')->shouldNotBeCalled();
        $collection->attach('e2')->shouldNotBeCalled();
        $collection->attach('e3')->shouldBeCalled();

        $this->entitiesShouldHaveBeen('1', 'entities', 'created')->shouldReturn(null);
        $this->shouldThrow(new \Exception('0 entities should have been created, 1 actually', 1))->duringEntitiesShouldHaveBeen('no', 'entities', 'created');
    }

    function it_should_throw_an_exception_if_no_existence(
        EntityResolver $resolver,
        TableNode $tableNode,
        $repository,
        $manager,
        ClassMetadata $metadata
    ) {
        $resolver->resolve(Argument::cetera())->willReturn([$metadata]);
        $metadata->getName()->willReturn("EntityStub");

        $tableNode->getRows()->willReturn(array(
            array('firstName', 'lastName', 'number', 'nullValue'),
            array('John', 'DOE', 0, ''),
        ));

        $manager->getRepository(Argument::any())->willReturn($repository);
        $repository->findOneBy(['firstName' => 'John', 'lastName' => 'DOE', 'number' => 0, 'nullValue' => null])->willReturn(null);

        $this->shouldThrow(new \Exception("There is no object for the following criteria: {\"firstName\":\"John\",\"lastName\":\"DOE\",\"number\":0,\"nullValue\":null}"))->duringExistLikeFollowing(1, "Class", $tableNode);
    }
}

class EntityStub
{
    private $correctProperty;

    private $incorrectProperty;

    public function __construct($correct, $incorrect)
    {
        $this->correctProperty = $correct;
        $this->incorrectProperty = $incorrect;
    }

    public function getCorrectProperty()
    {
        return $this->correctProperty;
    }

    public function getIncorrectProperty()
    {
        return $this->incorrectProperty;
    }
}
