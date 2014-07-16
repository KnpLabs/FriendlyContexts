<?php

namespace Knp\FriendlyExtension\Context\Helper;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Knp\FriendlyExtension\Context\Helper\HelperInterface;
use Knp\FriendlyExtension\Doctrine\Resolver;

class DoctrineHelper implements HelperInterface
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

    /**
     * Reset the schema, clear the entity manager
     *
     * @return void
     */
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

        $this->em->clear();
    }
}
