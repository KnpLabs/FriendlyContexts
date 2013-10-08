<?php

namespace Knp\FriendlyContexts;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Knp\FriendlyContexts\Reflection\ObjectReflector;
use Knp\FriendlyContexts\Doctrine\EntityHydrator;
use Knp\FriendlyContexts\Record\Collection\Bag;
use Knp\FriendlyContexts\Doctrine\EntityResolver;
use Knp\FriendlyContexts\Guesser\GuesserManager;
use Knp\FriendlyContexts\Tool\TextFormater;
use Symfony\Component\DependencyInjection\Container as BaseContainer;

class Container extends BaseContainer
{

    protected $options = [];
    protected $container;

    public function __construct(ContainerInterface $container, $options)
    {
        $this->container = $container;
        $this->setOptions($options);

        $services = [
            'friendly.context.guesser.manager'  => new GuesserManager,
            'friendly.context.object.reflector' => new ObjectReflector,
            'friendly.context.entity.hydrator'  => new EntityHydrator,
            'friendly.context.entity.resolver'  => new EntityResolver,
            'friendly.context.record.bag'       => new Bag,
            'friendly.context.text.formater'    => new TextFormater,
        ];

        foreach ($services as $name => $service) {
            if ($this->isContainable($service)) {
                $service->setContainer($this);
            }
            $this->set($name, $service);
        }
    }

    public function setOptions($options = [])
    {
        $this->options = array_merge(
            $this->options,
            $options
        );

        return $this;
    }

    public static function isContainable($service)
    {
        $rfl = new \ReflectionClass($service);

        $traits = $rfl->getTraitNames();

        return in_array('Knp\FriendlyContexts\Dictionary\Containable', $traits);
    }
}
