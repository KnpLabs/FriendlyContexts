<?php

namespace Knp\FriendlyExtension\Type;

use Knp\FriendlyExtension\Type\Guesser\GuesserInterface;

class GuesserRegistry
{
    protected $guessers = [];

    public function addGuesser(GuesserInterface $guesser)
    {
        array_unshift($this->guessers, $guesser);
    }

    public function find(array $mapping)
    {
        foreach ($this->guessers as $g) {
            if ($g->supports($mapping)) {
                return $g;
            }
        }

        return false;
    }
}
