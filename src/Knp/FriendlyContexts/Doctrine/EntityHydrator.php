<?php

namespace Knp\FriendlyContexts\Doctrine;

use Doctrine\ORM\Mapping\ClassMetadata;
use Knp\FriendlyContexts\Dictionary\Containable;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Doctrine\Common\Collections\ArrayCollection;

class EntityHydrator
{
    use Containable;

    public function hydrate(ObjectManager $em, $entity, $values)
    {
        foreach ($values as $property => $value) {
            $mapping = $this->getEntityResolver()->getMetadataFromProperty($em, $entity, $property);
            $collectionRelation = in_array($mapping['type'], [ClassMetadata::ONE_TO_MANY, ClassMetadata::MANY_TO_MANY]);
            $arrayRelation = $mapping['type'] === 'array';

            $result = [];;

            if ($collectionRelation || $arrayRelation) {
                $result = array_map(
                    function($e) {
                        return $this->format($mapping, $e);
                    },
                    $this->getTextFormater()->listToArray($value)
                );

                $result = $collectionRelation ? new ArrayCollection($result) : $result;
            } else {
                $result = $this->format($mapping, $value);
            }

            PropertyAccess::getPropertyAccessor()
                ->setValue(
                    $entity,
                    $mapping['fieldName'],
                    $result
                )
            ;
        }
    }

    protected function format($mapping, $value)
    {
        if (false === $guesser = $this->getGuesserManager()->find($mapping)) {
            return $value;
        }

        return $guesser->transform($value, $mapping);
    }
}
