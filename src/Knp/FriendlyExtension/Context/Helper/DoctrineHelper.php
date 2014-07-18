<?php

namespace Knp\FriendlyExtension\Context\Helper;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Knp\FriendlyExtension\Context\Helper\AbstractHelper;
use Knp\FriendlyExtension\Doctrine\Resolver;

class DoctrineHelper extends AbstractHelper
{
    private $em;
    private $resolver;

    public function __construct(EntityManagerInterface $em, Resolver $resolver)
    {
        $this->em       = $em;
        $this->resolver = $resolver;
    }

    public function getName()
    {
        return 'doctrine';
    }

    public function getClass($name)
    {
        return class_exists($name)
            ? $name
            : $this->resolver->resolveEntity($name, true)
        ;
    }

    public function getRepository($name)
    {
        return $this->em->getRepository($this->getClass($name));
    }

    public function all($name)
    {
        return $this
            ->getRepository($name)
            ->createQueryBuilder('o')
            ->getQuery()
            ->getResult()
        ;
    }

    public function refresh($entity)
    {
        $this->em->refresh($entity);
    }

    public function remove($entity)
    {
        $this->em->remove($entity);
    }

    public function persist($entity)
    {
        $this->em->persist($entity);
    }

    public function flush($entity = null)
    {
        $this->em->flush($entity);
    }

    public function reset()
    {
        $metadata = $this
            ->em
            ->getMetadataFactory()
            ->getAllMetadata()
        ;

        if (!empty($metadata)) {
            $tool = new SchemaTool($this->em);
            $tool->dropSchema($metadata);
            $tool->createSchema($metadata);
        }
    }

    public function clear()
    {
        $this->em->clear();
    }
}
