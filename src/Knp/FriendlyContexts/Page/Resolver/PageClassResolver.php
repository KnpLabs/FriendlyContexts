<?php

namespace Knp\FriendlyContexts\Page\Resolver;

use Behat\Mink\Session;
use Behat\Mink\Element\DocumentElement;
use Doctrine\Common\Inflector\Inflector;

class PageClassResolver
{
    private $namespace;

    public function __construct($namespace = '')
    {
        $this->namespace = $namespace;
    }

    public function exists($class)
    {
        return class_exists($class);
    }

    public function resolveName($name)
    {
        $class = sprintf(
            '%s\\%sPage',
            $this->namespace,
            ucfirst(Inflector::camelize(str_replace(' ', '_', $name)))
        );

        return $class;
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
