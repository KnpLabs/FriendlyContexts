<?php

namespace Knp\FriendlyExtension\Entity;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Knp\FriendlyContexts\Utils\UniqueCache;
use Knp\FriendlyExtension\Type\GuesserRegistry;
use Knp\FriendlyExtension\Utils\ObjectReflector;
use Knp\FriendlyExtension\Utils\TextFormater;

class Hydrator
{
    public function __construct(EntityManagerInterface $em, TextFormater $formater, GuesserRegistry $guessers, Resolver $resolver, UniqueCache $cache)
    {
        $this->em       = $em;
        $this->formater = $formater;
        $this->guessers = $guessers;
        $this->resolver = $resolver;
        $this->cache    = $cache;
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

    protected function formatFromMapping(array $mapping, &$property, &$value)
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
