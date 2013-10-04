<?php

namespace Knp\FriendlyContexts\Doctrine;

use Doctrine\ORM\Mapping\ClassMetadata;
use Knp\FriendlyContexts\Dictionary\FacadableInterface;
use Knp\FriendlyContexts\Dictionary\Facadable;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Doctrine\Common\Collections\ArrayCollection;

class EntityHydrator implements FacadableInterface
{
    use Facadable;

    public function hydrate(ObjectManager $em, $entity, $values)
    {
        foreach ($values as $property => $value) {
            $mapping = $this->getDeps('entity.resolver')->getMetadataFromProperty($em, $entity, $property);
            $entityRelation = array_key_exists('isOwningSide', $mapping);
            $collectionRelation = in_array($mapping['type'], [ClassMetadata::ONE_TO_MANY, ClassMetadata::MANY_TO_MANY]);
            $arrayRelation = $mapping['type'] === 'array';

            $result = [];;

            if ($collectionRelation || $arrayRelation) {
                $value = $this->getDeps('text.formater')->listToArray($value);

                foreach ($value as $single) {
                    $result[] = $this->format($mapping, $single);
                }

                if ($collectionRelation) {
                    $result = new ArrayCollection($result);
                }
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
        if (false === $guesser = $this->getDeps('guesser.manager')->find($mapping)) {
            return $value;
        }

        return $guesser->transform($value, $mapping);
    }
}
