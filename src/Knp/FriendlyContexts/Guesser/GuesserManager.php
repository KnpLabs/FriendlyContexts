<?php

namespace Knp\FriendlyContexts\Guesser;

use Doctrine\Common\Proxy\Exception\InvalidArgumentException;
use Knp\FriendlyContexts\Dictionary\Containable;

class GuesserManager
{
    use Containable;

    protected $classes = [];
    protected $guessers = [];

    public function __construct()
    {
        $this->classes = [
            'Knp\FriendlyContexts\Guesser\DatetimeGuesser' => 'DateTime',
            'Knp\FriendlyContexts\Guesser\BooleanGuesser' => null,
            'Knp\FriendlyContexts\Guesser\EntityGuesser' => null,
        ];

        $this->load();
    }

    public function addGuesser($guesser, $fakers = null)
    {
        if (is_string($guesser)) {
            $guesser = new $guesser;
        }

        if (false === $guesser instanceof GuesserInterface) {
            throw new \InvalidArgumentException('Your guesser should implements Knp\FriendlyContexts\Guesser\GuesserInterface');
        }

        $guesser->setManager($this);
        $guesser->setFakers(is_string($fakers) ? [ $fakers ] : $fakers);

        array_unshift($this->guessers, $guesser);
    }

    public function find($mapping)
    {
        foreach ($this->guessers as $g) {
            if ($this->isContainable($g)) {
                $this->getOrRegister('friendly.context.guesser.' . $g->getName(), function () use ($g) { return $g; });
            }
            if ($g->supports($mapping)) {
                return $g;
            }
        }

        return false;
    }

    public function load()
    {
        $this->guessers = [];

        foreach ($this->classes as $class => $fakers){
            $this->addGuesser($class, $fakers);
        }
    }
}
