<?php

namespace Knp\FriendlyContexts;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Knp\FriendlyContexts\Reflection\ObjectReflector;
use Knp\FriendlyContexts\Doctrine\EntityHydrator;
use Knp\FriendlyContexts\Record\Collection\Bag;
use Knp\FriendlyContexts\Doctrine\EntityResolver;
use Knp\FriendlyContexts\Dictionary\FacadableInterface;
use Knp\FriendlyContexts\Guesser\GuesserManager;
use Knp\FriendlyContexts\Tool\TextFormater;

class Container
{

    protected $options = [];
    protected $container;

    public function __construct(ContainerInterface $container, $options)
    {
        $this->container = $container;
        $this->setOptions($options);

        $this
            ->set('friendly.context.guesser.manager',  new GuesserManager)
            ->set('friendly.context.object.reflector', new ObjectReflector)
            ->set('friendly.context.entity.hydrator',  new EntityHydrator)
            ->set('friendly.context.entity.resolver',  new EntityResolver)
            ->set('friendly.context.record.bag',       new Bag)
            ->set('friendly.context.text.formater',    new TextFormater)
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

    public function has($name)
    {
        return $this->container->has($name);
    }

    public function get($name)
    {
        return $this->container->get($name, null);
    }

    public function set($name, $value)
    {
        if ($this->isContainable($value)) {
            $value->setContainer($this);
        }

        $this->container->set($name, $value);

        return $this;
    }

    public static function isContainable($object)
    {
        $rfl = new \ReflectionClass($object);

        $traits = $rfl->getTraitNames();

        return in_array('Knp\\FriendlyContext\\Dictionary\\Containable', $traits);
    }
}
