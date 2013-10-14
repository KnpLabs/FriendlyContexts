<?php

namespace Knp\FriendlyContexts\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Inflector\Inflector;
use Knp\FriendlyContexts\Dictionary\Containable;

class EntityResolver
{
    use Containable;

    public function resolve(ObjectManager $entityManager, $name, $namespaces)
    {
        $results = [];

        $namespaces = is_array($namespaces) ? $namespaces : [ $namespaces ];

        foreach ($namespaces as $namespace) {
            $results = $this->getClassesFromName($entityManager, $name, $namespace, $results);
        }

        return (0 < count($results)) ? $results : null;
    }

    protected function getClassesFromName(ObjectManager $entityManager, $name, $namespace, array $results = [])
    {
        $allMetadata = $entityManager->getMetadataFactory()->getAllMetadata();
        $allClass = $this->getObjectReflector()->getReflectionsFromMetadata($allMetadata);
        foreach ($this->entityNameProposal($name) as $name) {
            $class = array_filter(
                $allClass,
                function ($e) use ($namespace, $name) {
                    $nameValid = strtolower($e->getShortName()) === strtolower($name);

                    return '' === $namespace
                        ? $nameValid
                        : $namespace === substr($e->getNamespaceName(), 0, strlen($namespace)) && $nameValid
                    ;
                }
            );
            $results = array_merge($results, $class);
        }

        return $results;
    }

    public function getMetadataFromProperty(ObjectManager $entityManager, $entity, $property)
    {
        $metadata     = $this->getMetadataFromObject($entityManager, $entity);

        if (null !== $map = $this->getMappingFromMetadata($metadata->fieldMappings, $property)) {
            return $map;
        }

        if (null !== $map = $this->getMappingFromMetadata($metadata->associationMappings, $property)) {
            return $map;
        }

        throw new \RuntimeException(
            sprintf(
                'Can\'t find property %s or %s in class %s',
                $this->getTextFormater()->toCamelCase(strtolower($property)),
                $this->getTextFormater()->toUnderscoreCase(strtolower($property)),
                get_class($entity())
            )
        );
    }

    public function getMetadataFromObject(ObjectManager $entityManager, $object)
    {
        return $entityManager
            ->getMetadataFactory()
            ->getMetadataFor(get_class($object)
        );
    }

    public function entityNameProposal($name)
    {
        $name = strtolower(str_replace(" ", "", $name));

        $results = [Inflector::singularize($name), Inflector::pluralize($name), $name];

        return array_unique($results);
    }

    protected function getMappingFromMetadata($metadata, $property)
    {
        foreach ($metadata as $id => $map) {
            switch (strtolower($id)) {
                case strtolower($property):
                case $this->getTextFormater()->toCamelCase(strtolower($property)):
                case $this->getTextFormater()->toUnderscoreCase(strtolower($property)):
                    return $map;
            }
        }

        return null;
    }

}
