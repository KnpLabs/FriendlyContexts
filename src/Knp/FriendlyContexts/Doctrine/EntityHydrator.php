<?php

namespace Knp\FriendlyContexts\Doctrine;

use Doctrine\ORM\Mapping\ClassMetadata;
use Knp\FriendlyContexts\Record\Record;

class EntityHydrator
{
    public function hydrate(Record $record, $values)
    {
        foreach ($values as $property => $value) {
            $mapping = $this->resolveProperty($record, $property, $value);
            $entityRelation = array_key_exists('isOwningSide', $mapping);
            $collectionRelation = in_array($mapping['type'], [ClassMetadata::ONE_TO_MANY, ClassMetadata::MANY_TO_MANY]);
            $arrayRelation = $mapping['type'] === 'array';

            if ($collectionRelation || $arrayRelation) {
                $value = $this->listToArray($value);
            }

            if (!array_key_exists('isOwningSide', $mapping)) {
                switch ($mapping['type']) {
                case 'array':
                    $value = $this->listToArray($value);
                default:
                    $this->accessor->setValue($entity, $mapping['fieldName'], $value);
                    break;
                }
            } else {
                $targetEntity = $mapping['targetEntity'];
                if (null === $entityCollection = $this->collections->get($targetEntity)) {
                    throw new \Exception(sprintf("Can't find collection for %s", $targetEntity));
                }

                if (in_array($mapping['type'], [ClassMetadata::ONE_TO_MANY, ClassMetadata::MANY_TO_MANY])) {
                    $records = new ArrayCollection;
                    foreach ($this->listToArray($value) as $v) {
                        if (null === $targetRecord = $entityCollection->search($v)) {
                            throw new \Exception(sprintf("Can't find %s with value %s", $targetEntity, $v));
                        }
                        $records->add($targetRecord->getEntity());
                    }
                    $this->accessor->setValue($entity, $mapping['fieldName'], $records);
                } else {
                    if (null === $targetRecord = $entityCollection->search($value)) {
                        throw new \Exception(sprintf("Can't find %s with value %s", $targetEntity, $value));
                    }
                    $this->accessor->setValue($entity, $mapping['fieldName'], $targetRecord->getEntity());
                }
            }
        }
    }
}
