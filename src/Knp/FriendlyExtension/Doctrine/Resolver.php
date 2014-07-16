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
        $results = [];

        foreach ($this->getMetadata() as $metadata) {
            if ($this->proposer->match($name, $this->reflector->getClassShortName($metadata->name))) {
                $results[] = $this->reflector->getClassLongName($metadata->name);
            }
        }

        if (true === $onlyOne && 1 > count($results)) {
            $message = sprintf(
                'Expected only one entity named "%s", "%s" found',
                $name,
                implode('", "', $results)
            );
            throw new \Exception($message);
        }

        return $onlyOne
            ? empty($entities) ? null : current($entities)
            : $entities
        ;
    }

    public function getMetadataFor($class, $property = null)
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

    public function getMetadata()
    {
        return $this->em->getMetadataFactory()->getAllMetadata();
    }

    private function resolveName($name, $names)
    {
        foreach ($names as $property) {
            if ($this->proposer->match($name, $property)) {

                return $property;
            }
        }
    }
}
