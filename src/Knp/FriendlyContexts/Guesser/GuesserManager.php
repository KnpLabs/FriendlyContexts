<?php

namespace Knp\FriendlyContexts\Guesser;

use Doctrine\Common\Proxy\Exception\InvalidArgumentException;

class GuesserManager
{
    protected $guessers = [];

    public function __construct()
    {
        $classes = [
            'Knp\FriendlyContexts\Guesser\DatetimeGuesser',
            'Knp\FriendlyContexts\Guesser\BooleanGuesser',
        ];

        foreach ($classes as $c){
            $this->addGuesser($c);
        }
    }

    public function addGuesser($guesser)
    {
        if (is_string($guesser)) {
            $guesser = new $guesser;
        }

        if (false === $guesser instanceof GuesserInterface) {
            throw new \InvalidArgumentException('Your guesser should implements Knp\FriendlyContexts\Guesser\GuesserInterface');
        }

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
