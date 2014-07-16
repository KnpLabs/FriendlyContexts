<?php

namespace Knp\FriendlyExtension\Context\Helper;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Knp\FriendlyExtension\Context\Helper\HelperInterface;

class DoctrineHelper implements HelperInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getName()
    {
        return 'doctrine';
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
