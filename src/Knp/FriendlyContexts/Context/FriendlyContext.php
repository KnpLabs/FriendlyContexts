<?php

namespace Knp\FriendlyContexts\Context;

use Symfony\Component\DependencyInjection\ContainerInterface;

class FriendlyContext extends Context
{
    public function initialize(array $config, ContainerInterface $container)
    {
        parent::initialize($config, $container);

        foreach (array_keys($config['Contexts']) as $name) {
            $this->loadContext($name, $config);
        }
    }

    protected function loadContext($name, array $config)
    {
        $class = sprintf('Knp\FriendlyContexts\Context\%sContext', $name);

        if (!class_exists($class)) {
            return;
        }

        $context = new $class;

        if ($context instanceof FriendlyContext) {
            return;
        }

        $context->initialize($config, $this->container);

        $this->useContext($name, $context);
    }

    protected function getDefaultOptions()
    {
        return [
            'Contexts' => []
        ];
    }
}
