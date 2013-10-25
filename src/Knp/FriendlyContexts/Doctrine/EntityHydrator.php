<?php

namespace Knp\FriendlyContexts\Doctrine;

use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Doctrine\Common\Collections\ArrayCollection;
use Knp\FriendlyContexts\Tool\TextFormater;
use Knp\FriendlyContexts\Guesser\GuesserManager;
use Doctrine\Common\Persistence\ObjectManager;

class EntityHydrator
{
    public function __construct(TextFormater $formater, GuesserManager $guesserManager, EntityResolver $resolver)
    {
        $this->formater       = $formater;
        $this->guesserManager = $guesserManager;
        $this->resolver       = $resolver;
    }

    public function hydrate(ObjectManager $em, $entity, $values)
    {
        foreach ($values as $property => $value) {
            $mapping = $this->resolver->getMetadataFromProperty($em, $entity, $property);
            $collectionRelation = in_array($mapping['type'], [ClassMetadata::ONE_TO_MANY, ClassMetadata::MANY_TO_MANY]);
            $arrayRelation = $mapping['type'] === 'array';

            $result = null;

            if ($collectionRelation || $arrayRelation) {
                $result = array_map(
                    function ($e) use ($mapping) {
                        return $this->format($mapping, $e);
                    },
                    $this->formater->listToArray($value)
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
        if (false === $guesser = $this->guesserManager->find($mapping)) {
            return $value;
        }

        return $guesser->transform($value, $mapping);
    }
}
