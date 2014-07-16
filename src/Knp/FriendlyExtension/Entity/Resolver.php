<?php

namespace Knp\FriendlyExtension\Entity;

use Doctrine\ORM\EntityManagerInterface;
use Knp\FriendlyExtension\Utils\NameProposer;
use Knp\FriendlyExtension\Utils\ObjectReflector;
use Knp\FriendlyExtension\Utils\TextFormater;

class Resolver
{
    private $em;
    private $reflector;
    private $formater;
    private $nameProposer;

    public function __construct(
        EntityManagerInterface $em,
        ObjectReflector $reflector,
        TextFormater $formater,
        NameProposer $nameProposer
    )
    {
        $this->em           = $em;
        $this->reflector    = $reflector;
        $this->formater     = $formater;
        $this->nameProposer = $nameProposer;
    }

    public function resolveClassName($name, $namespaces = '')
    {
        $results = [];

        $namespaces = is_array($namespaces) ? $namespaces : [ $namespaces ];

        foreach ($namespaces as $namespace) {
            $results = $this->getClassesFromName($name, $namespace, $results);
        }

        return (0 < count($results)) ? $results : null;
    }

    public function getClassesFromName($name, $namespace, array $results = [])
    {
        $metadata = $this->em->getMetadataFactory()->getAllMetadata();
        $classes = $this->reflector->getReflectionsFromMetadata($metadata);

        return array_merge(
            $result,
            array_filter(
                $classes,
                function ($e) use ($name) {
                    return $this->nameProposer->match($e->getShortName($name));
                }
            )
        );
    }


    public function getObjectMetadata($object)
    {
        return $this
            ->em
            ->getMetadataFactory()
            ->getMetadataFor(is_object($object) ? get_class($object) : (string)$object)
        ;
    }

    public function getPropertyMetadata($object, $property)
    {
        $metadata = $this->getObjectMetadata($object);

        if (null !== $map = $this->getExtractPropertyMetadata($metadata->fieldMappings, $property)) {
            return $map;
        }

        if (null !== $map = $this->getExtractPropertyMetadata($metadata->associationMappings, $property)) {
            return $map;
        }
    }

    protected function getExtractPropertyMetadata($metadata, $property)
    {
        $property = trim($property);

        foreach ($metadata as $id => $mapping) {
            if ($this->nameProposer->match($property, $id)) {

                return $mapping;
            }
        }
    }
}
