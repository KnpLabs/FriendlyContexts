<?php

namespace Knp\FriendlyExtension\Type\Guesser;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\ClassMetadata;
use Knp\FriendlyExtension\Type\GuesserRegistry;
use Knp\FriendlyExtension\Type\Guesser\AbstractGuesser;
use Knp\FriendlyExtension\Utils\TextFormater;

final class ArrayGuesser extends AbstractGuesser
{
    private $registry;
    private $formater;

    public function __construct(GuesserRegistry $registry, TextFormater $formater)
    {
        $this->registry = $registry;
        $this->formater = $formater;
    }

    public function supports(array $mapping)
    {
        $mapping = array_merge([ 'type' => null ], $mapping);

        return 'array' === $mapping['type'];
    }

    public function transform($str, array $mapping = null)
    {
        return array_map(
            function ($e) use ($mapping) {
                unset($mapping['type']);
                return $this->format($mapping, $e);
            },
            $this->formater->listToArray($value)
        );
    }

    public function fake(array $mapping)
    {
        return [];
    }

    public function getName()
    {
        return 'array';
    }

    private function format($mapping, $value)
    {
        if (false === $guesser = $this->guesserManager->find($mapping)) {

            return $value;
        }

        return $guesser->transform($value, $mapping);
    }
}
