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

class EntityContextSpec extends ObjectBehavior
{
    /**
     * @param Symfony\Component\HttpKernel\KernelInterface $kernel
     * @param Symfony\Component\DependencyInjection\ContainerInterface $containerInterface
     * @param Doctrine\Common\Persistence\ManagerRegistry $doctrine
     * @param Doctrine\Common\Persistence\ObjectManager $manager
     * @param Doctrine\ORM\EntityRepository $repository
     * @param Knp\FriendlyContexts\Doctrine\EntityResolver $resolver
     * @param Knp\FriendlyContexts\Record\Collection\Bag $bag
     * @param Knp\FriendlyContexts\Record\Collection $collection
     * @param \ReflectionClass $reflectionClass
     **/
    function let(KernelInterface $kernel, ContainerInterface $containerInterface, ManagerRegistry $doctrine, ObjectManager $manager, EntityRepository $repository, EntityResolver $resolver, Bag $bag, Collection $collection, \ReflectionClass $reflectionClass)
    {
        $kernel->getContainer()->willReturn($containerInterface);
        $containerInterface->set(Argument::any(), Argument::any())->willReturn(true);
        $containerInterface->has('friendly.context.container')->willReturn(true);
        $containerInterface->get('doctrine')->willReturn($doctrine);
        $containerInterface->get('friendly.context.entity.resolver')->willReturn($resolver);
        $containerInterface->get('friendly.context.entity.resolver')->willReturn($resolver);
        $containerInterface->get('friendly.context.record.bag')->willReturn($bag);
        $doctrine->getManager()->willReturn($manager);
        $manager->getRepository(Argument::any())->willReturn($repository);
        $repository->findAll()->willReturn(['', '']);
        $resolver->resolve($manager, 'entities', [""])->willReturn([$reflectionClass]);
        $bag->getCollection(Argument::any())->willReturn($collection);

        $this->beConstructedWith([]);
        $this->setKernel($kernel);
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
