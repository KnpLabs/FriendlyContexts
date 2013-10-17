<?php

namespace Knp\FriendlyContexts\Guesser;

abstract class AbstractGuesser
{
    protected $manager;

    public function getManager()
    {
        return $this->manager;
    }

    public function setManager(GuesserManager $manager)
    {
        $this->manager = $manager;

        return $this;
    }

    protected function get($name)
    {
        return $this->getManager()->getContainer()->get($name);
    }
}
