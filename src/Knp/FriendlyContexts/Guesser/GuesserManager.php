<?php

namespace Knp\FriendlyContexts\Guesser;

class GuesserManager
{
    protected $classes = [];
    protected $guessers = [];

    public function addGuesser(GuesserInterface $guesser)
    {
        $guesser->setManager($this);

        array_unshift($this->guessers, $guesser);
    }

    public function find($mapping)
    {
        foreach ($this->guessers as $g) {
            if ($g->supports($mapping)) {
                return $g;
            }
        }

        return false;
    }
}
