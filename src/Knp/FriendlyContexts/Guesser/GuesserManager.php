<?php

namespace Knp\FriendlyContexts\Guesser;

use Doctrine\Common\Proxy\Exception\InvalidArgumentException;
use Knp\FriendlyContexts\Dictionary\Containable;

class GuesserManager
{
    use Containable;

    protected $classes = [];
    protected $guessers = [];
    protected $loaded = false;

    public function __construct()
    {
        $this->classes = [
            'Knp\FriendlyContexts\Guesser\DatetimeGuesser',
            'Knp\FriendlyContexts\Guesser\BooleanGuesser',
            'Knp\FriendlyContexts\Guesser\EntityGuesser',
        ];

        $this->load();
    }

    public function addGuesser($guesser)
    {
        if (is_string($guesser)) {
            $guesser = new $guesser;
        }

        if (false === $guesser instanceof GuesserInterface) {
            throw new \InvalidArgumentException('Your guesser should implements Knp\FriendlyContexts\Guesser\GuesserInterface');
        }

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

    public function load()
    {
        $this->load = true;
        $this->guessers = [];

        foreach ($this->classes as $c){
            $this->addGuesser($c);
        }
    }
}
