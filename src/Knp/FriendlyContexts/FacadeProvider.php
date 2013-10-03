<?php

namespace Knp\FriendlyContexts;

use Knp\FriendlyContexts\Reflection\ObjectReflector;
use Knp\FriendlyContexts\Doctrine\EntityHydrator;
use Knp\FriendlyContexts\Record\Collection\Bag;
use Knp\FriendlyContexts\Doctrine\EntityResolver;
use Knp\FriendlyContexts\Dictionary\FacadableInterface;

class FacadeProvider
{

    protected $options = [];
    protected $deps = [];

    public function __construct($options)
    {
        $this->setOptions($options);

        $this
            ->setDeps('object.reflector', new ObjectReflector)
            ->setDeps('entity.hydrator',  new EntityHydrator)
            ->setDeps('entity.resolver',  new EntityResolver)
            ->setDeps('record.bag',       new Bag)
        ;
    }

    public function setOptions($options = [])
    {
        $this->options = array_merge(
            $this->options,
            $options
        );

        return $this;
    }

    public function getDeps($name)
    {
        return $this->deps[$name];
    }

    public function setDeps($name, $value)
    {
        $this->deps[$name] = $value;

        if ($value instanceof FacadableInterface) {
            $value->setFacadeProvider($this);
        }

        return $this;
    }
}
