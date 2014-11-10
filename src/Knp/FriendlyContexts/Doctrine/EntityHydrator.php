<?php

namespace Knp\FriendlyContexts\Doctrine;

use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Doctrine\Common\Collections\ArrayCollection;
use Knp\FriendlyContexts\Utils\TextFormater;
use Knp\FriendlyContexts\Guesser\GuesserManager;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\FriendlyContexts\Utils\UniqueCache;

class EntityHydrator
{
    public function __construct(TextFormater $formater, GuesserManager $guesserManager, EntityResolver $resolver, UniqueCache $cache)
    {
        $this->formater       = $formater;
        $this->guesserManager = $guesserManager;
        $this->resolver       = $resolver;
        $this->cache          = $cache;
    }

    public function hydrate(ObjectManager $em, $entity, $values)
    {
        foreach ($values as $property => $value) {
            if (false !== $mapping = $this->resolver->getMetadataFromProperty($em, $entity, $property)) {
                $this->formatFromMapping($mapping, $property, $value);
            }

            try {
                PropertyAccess::getPropertyAccessor()
                    ->setValue(
                        $entity,
                        $this->formater->toCamelCase($property),
                        $value
                    )
                ;
            } catch (\Exception $e) {
                if (!($value instanceof ArrayCollection)) {
                    throw $e;
                }

                PropertyAccess::getPropertyAccessor()
                    ->setValue(
                        $entity,
                        $this->formater->toCamelCase($property),
                        $value->toArray()
                    )
                ;
            }
        }

        return $this;
    }

    public function completeRequired(ObjectManager $em, $entity)
    {
        $this->completeFields($em, $entity);
    }

    public function completeFields(ObjectManager $em, $entity)
    {
        $accessor = PropertyAccess::getPropertyAccessor();

        $metadata = $this->resolver->getMetadataFromObject($em, $entity);

        foreach ($metadata->getColumnNames() as $columnName) {
            $property = $metadata->getFieldName($columnName);
            if (false === $metadata->isNullable($property) && null === $accessor->getValue($entity, $property)) {
                try {
                    $accessor->setValue(
                        $entity,
                        $property,
                        $this->complete($metadata->getFieldMapping($property), $metadata->getName())
                    );
                } catch (\Exception $ex) {
                    unset($ex);
                }
            }
        }

        return $this;
    }

    protected function complete($mapping, $className)
    {
        if (false === $guesser = $this->guesserManager->find($mapping)) {
            throw new \Exception(sprintf('There is no fake solution for "%s" typed fields', $mapping['type']));
        }

        if (true === $mapping['unique']) {
            return $this->cache->generate($className, $mapping['fieldName'], function () use ($guesser, $mapping) {
                return $guesser->fake($mapping);
            });
        }

        return $guesser->fake($mapping);
    }

    protected function format($mapping, $value)
    {
        if (false === $guesser = $this->guesserManager->find($mapping)) {

            return $value;
        }

        return $guesser->transform($value, $mapping);
    }

    protected function formatFromMapping($mapping, &$property, &$value)
    {
        $property = $mapping['fieldName'];
        $collectionRelation = in_array($mapping['type'], [ClassMetadata::ONE_TO_MANY, ClassMetadata::MANY_TO_MANY]);
        $arrayRelation = $mapping['type'] === 'array';

        if ($collectionRelation || $arrayRelation) {
            $result = array_map(
                function ($e) use ($mapping) {
                    return $this->format($mapping, $e);
                },
                    $this->formater->listToArray($value)
                );

            $value = $collectionRelation ? new ArrayCollection($result) : $result;
        } else {
            $value = $this->format($mapping, $value);
        }
    }
}
