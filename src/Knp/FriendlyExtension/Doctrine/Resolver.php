<?php

namespace Knp\FriendlyExtension\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Knp\FriendlyExtension\Utils\NameProposer;
use Knp\FriendlyExtension\Utils\ObjectReflector;
use Knp\FriendlyExtension\Utils\TextFormater;

class Resolver
{
    private $em;
    private $reflector;
    private $formater;
    private $proposer;

    public function __construct(EntityManagerInterface $em, ObjectReflector $reflector, TextFormater $formater, NameProposer $proposer)
    {
        $this->em        = $em;
        $this->reflector = $reflector;
        $this->formater  = $formater;
        $this->proposer  = $proposer;
    }

    public function resolveEntity($name, $onlyOne = false)
    {
        $entities = array_filter(
            $this->getAllEntities(),
            function ($e) use ($name) {
                return $this->proposer->match($name, $e->getShortName());
            }
        );

        if (true === $onlyOne && 1 > count($entities)) {
            throw new \Exception(sprintf('Expected only one entity named "%s", "%s" found', $name,
                implode('", "',
                    array_map(
                        function ($e) { return $e->getShortName(); },
                        $entities
                    ),
                )
            ));
        }

        return $onlyOne
            ? empty($entities) ? null : current($entities)
            : $entities
        ;
    }

    public function resolveProperty($name, $property)
    {
        $entity = $this->resolveEntity($name, true);


    }

    private function getAllEntities()
    {
        $metadata = $this->em->getMetadataFactory()->getAllMetadata();

        return $this->reflector->getReflectionsFromMetadata($allMetadata);
    }

    private function getMetadataFor($class, $property = null)
    {
        $metadata = $this->em->getMetadataFactory()->getMetadataFor($class);

        if (null === $property) {

            return $metadata;
        }

        if (null !== $name = $this->resolveName($property, array_keys($metadata->fieldMappings))) {

            return $metadata->fieldMappings[$name];
        }

        if (null !== $name = $this->resolveName($property, array_keys($metadata->associationMappings))) {

            return $metadata->associationMappings[$name];
        }
    }
}
