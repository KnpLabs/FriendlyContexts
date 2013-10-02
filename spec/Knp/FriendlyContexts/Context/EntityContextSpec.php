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

class EntityContextSpec extends ObjectBehavior
{
    /**
     * @param Symfony\Component\HttpKernel\KernelInterface $kernel
     * @param Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param Doctrine\Common\Persistence\ManagerRegistry $doctrine
     * @param Doctrine\Common\Persistence\ObjectManager $manager
     * @param Doctrine\ORM\EntityRepository $repository
     * @param Knp\FriendlyContexts\Doctrine\EntityResolver $resolver
     * @param \ReflectionClass $reflectionClass
     **/
    function let(KernelInterface $kernel, ContainerInterface $container, ManagerRegistry $doctrine, ObjectManager $manager, EntityRepository $repository, EntityResolver $resolver, \ReflectionClass $reflectionClass)
    {
        $this->beConstructedWith([], $resolver);

        $this->setKernel($kernel);

        $kernel->getContainer()->willReturn($container);
        $container->get('doctrine')->willReturn($doctrine);
        $doctrine->getManager()->willReturn($manager);
        $manager->getRepository(Argument::any())->willReturn($repository);
        $repository->findAll()->willReturn(['', '']);
        $resolver->resolve('entities', [""])->willReturn([$reflectionClass]);
    }


    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Context\EntityContext');
    }

    function it_should_assert_a_creation()
    {
        $this->entitiesShouldBeCreated('2', 'entities')->shouldReturn(null);
        $this->shouldThrow(new \Exception('0 entities should be created, 2 in reality', 1))->duringEntitiesShouldBeCreated('no', 'entities');
    }

    function it_should_assert_a_deletion()
    {
        $this->entitiesShouldBeDeleted('no', 'entities')->shouldReturn(null);
        $this->shouldThrow(new \Exception('2 entities should be deleted, 0 in reality', 1))->duringEntitiesShouldBeDeleted('2', 'entities');
    }
}
