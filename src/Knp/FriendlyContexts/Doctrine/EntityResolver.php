<?php

namespace Knp\FriendlyContexts\Doctrine;

use Doctrine\Common\Inflector\Inflector;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Knp\FriendlyContexts\Reflection\ObjectReflector;
use Knp\FriendlyContexts\Utils\TextFormater;

class EntityResolver
{
    const CASE_CAMEL      = 'CamelCase';
    const CASE_UNDERSCORE = 'UnderscoreCase';

    protected $reflector;
    protected $formater;

    public function __construct(ObjectReflector $reflector, TextFormater $formater)
    {
        $this->reflector = $reflector;
        $this->formater  = $formater;
    }

    public function resolve(ObjectManager $entityManager, $name, $namespaces = '')
    {
        $results = [];

        $namespaces = is_array($namespaces) ? $namespaces : [ $namespaces ];

        foreach ($namespaces as $namespace) {
            $results = $this->getClassesFromName($entityManager, $name, $namespace, $results);
        }

        if (0 === count($results)) {

            return;
        }

        return $results;
    }

    protected function getClassesFromName(ObjectManager $entityManager, $name, $namespace, array $results = [])
    {
        if (!empty($results)) {

            return $results;
        }

        $allMetadata = $entityManager->getMetadataFactory()->getAllMetadata();
        $allClass = $this->reflector->getReflectionsFromMetadata($allMetadata);
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
        $metadata = $this->getMetadataFromObject($entityManager, $entity);

        if (null !== $map = $this->getMappingFromMetadata($metadata, $property)) {
            return $map;
        }

        if ($this->asAccessForCase($entity, $property, self::CASE_CAMEL) || $this->asAccessForCase($entity, $property, self::CASE_UNDERSCORE)) {
            return false;
        }

        throw new \RuntimeException(
            sprintf(
                'Can\'t find property %s or %s in class %s',
                $this->formater->toCamelCase(strtolower($property)),
                $this->formater->toUnderscoreCase(strtolower($property)),
                get_class($entity)
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

    public function asAccessForCase($entity, $property, $case)
    {
        $method = sprintf('to%s', $case);

        return property_exists($entity, $this->formater->{$method}($property)) || method_exists($entity, 'set' . $this->formater->{$method}($property));
    }

    protected function getMappingFromMetadata(ClassMetadata $metadata, $property)
    {
        if (null !== $map = $this->getMappingFromMetadataPart($metadata->fieldMappings, $property)) {
            return $map;
        }

        if (null !== $map = $this->getMappingFromMetadataPart($metadata->associationMappings, $property)) {
            return $map;
        }
    }

    protected function getMappingFromMetadataPart($metadata, $property)
    {
        $property = trim($property);

        foreach ($metadata as $id => $map) {
            switch (strtolower($id)) {
                case strtolower($property):
                case strtolower($this->formater->toCamelCase($property)):
                case strtolower($this->formater->toUnderscoreCase($property)):
                    return $map;
            }
        }

        return null;
    }

}
