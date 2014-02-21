<?php

namespace Knp\FriendlyContexts\Page\Resolver;

use Behat\Mink\Session;
use Behat\Mink\Element\DocumentElement;

class PageClassResolver
{
    public function exists($class)
    {
        return class_exists($class);
    }

    public function create(Session $session, $class)
    {
        if (!$this->exists($class)) {
            throw new \InvalidArgumentException(sprintf(
                'The page class "%s" can not be resolved.',
                $class
            ));
        }

        $instance = new $class($session);

        if (!$instance instanceof DocumentElement) {
            throw new \InvalidArgumentException(
                'A page class must be an instance of Behat\Mink\Element\DocumentElement.'
            );
        }

        return $instance;
    }
}
