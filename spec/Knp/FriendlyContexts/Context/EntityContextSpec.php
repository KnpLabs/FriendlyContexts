<?php

namespace spec\Knp\FriendlyContexts\Context;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use Knp\FriendlyContexts\Doctrine\EntityResolver;
use Knp\FriendlyContexts\Record\Collection\Bag;
use Knp\FriendlyContexts\Record\Collection;
use Knp\FriendlyContexts\Tool\Asserter;

class EntityContextSpec extends ObjectBehavior
{
    /**
     * @param Doctrine\Common\Persistence\ManagerRegistry $doctrine
     * @param Doctrine\Common\Persistence\ObjectManager $manager
     * @param Doctrine\ORM\EntityRepository $repository
     * @param Knp\FriendlyContexts\Doctrine\EntityResolver $resolver
     * @param Knp\FriendlyContexts\Record\Collection\Bag $bag
     * @param Knp\FriendlyContexts\Record\Collection $collection
     * @param \ReflectionClass $reflectionClass
     **/
    function let($doctrine, $manager, $repository, $resolver, $bag, $collection, \ReflectionClass $reflectionClass)
    {
        $doctrine->getManager()->willReturn($manager);
        $manager->getRepository(Argument::any())->willReturn($repository);
        $repository->findAll()->willReturn(['', '']);
        $resolver->resolve($manager, 'entities', [""])->willReturn([$reflectionClass]);
        $bag->getCollection(Argument::any())->willReturn($collection);

        $this->beConstructedWith([]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Context\EntityContext');
    }

    function it_should_assert_a_deletion()
    {
        $this->entitiesShouldBeDeleted('no', 'entities')->shouldReturn(null);
        $this->shouldThrow(new \Exception('2 entities should be deleted, 0 in reality', 1))->duringEntitiesShouldBeDeleted('2', 'entities');
    }

    function it_should_assert_a_creation()
    {
        $this->entitiesShouldBeCreated('2', 'entities')->shouldReturn(null);
        $this->shouldThrow(new \Exception('0 entities should be created, 2 in reality', 1))->duringEntitiesShouldBeCreated('no', 'entities');
    }
}
