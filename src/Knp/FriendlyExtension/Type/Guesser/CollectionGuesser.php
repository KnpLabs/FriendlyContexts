<?php

namespace Knp\FriendlyExtension\Type\Guesser;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\ClassMetadata;
use Knp\FriendlyExtension\Type\Guesser\AbstractGuesser;
use Knp\FriendlyExtension\Type\Guesser\ArrayGuesser;

final class CollectionGuesser extends AbstractGuesser
{
    public function __construct(ArrayGuesser $arrayGuesser)
    {
        $this->arrayGuesser = $arrayGuesser;
    }

    public function supports(array $mapping)
    {
        $mapping = array_merge([ 'type' => null ], $mapping);

        return in_array($mapping['type'], [ClassMetadata::ONE_TO_MANY, ClassMetadata::MANY_TO_MANY]);
    }

    public function transform($str, array $mapping = null)
    {
        return new ArrayCollection(
            $this->arrayGuesser->transform($str, $mapping)
        );
    }

    public function fake(array $mapping)
    {
        return new ArrayCollection(
            $this->arrayGuesser->fake($mapping)
        );
    }

    public function getName()
    {
        return 'array';
    }
}
