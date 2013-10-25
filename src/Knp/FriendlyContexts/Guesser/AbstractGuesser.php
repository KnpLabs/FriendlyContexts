<?php

namespace Knp\FriendlyContexts\Guesser;

use Knp\FriendlyContexts\Dictionary\Containable;

abstract class AbstractGuesser
{
    use Containable;

    protected $manager;
    protected $fakers = [];

    public function getManager()
    {
        return $this->manager;
    }

    public function setManager(GuesserManager $manager)
    {
        $this->manager = $manager;

        return $this;
    }

    public function setFakers(array $fakers = null)
    {
        $this->fakers = $fakers ?: [];

        return $this;
    }

    protected function get($name)
    {
        return $this->getManager()->getContainer()->get($name);
    }
}
