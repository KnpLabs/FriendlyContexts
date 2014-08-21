<?php

namespace Knp\FriendlyExtension\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class FakerGuesserRegistrationPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $guessers   = $container->findTaggedServiceIds('friendly.type.guesser');
        $priorities = $this->getPriorities($guessers);
        $guessers   = array_keys($guessers);
        $fakers     = array_keys($container->findTaggedServiceIds('friendly.faker.provider'));
        $regitry    = $container->getDefinition('friendly.type.guesser_registry');
        array_multisort($priorities, SORT_ASC, SORT_REGULAR, $guessers);

        foreach ($guessers as $guesser) {
            $definition = $container->getDefinition($guesser);
            foreach ($fakers as $faker) {
                $definition->addMethodCall('addFaker', [ new Reference($faker) ]);
            }
            $regitry->addMethodCall('addGuesser', [ new Reference($guesser) ]);
        }
    }

    public function getPriorities(array $services)
    {
        $priorities = [];

        foreach ($services as $service) {
            $priority = 0;
            foreach ($service as $tag) {
                if (array_key_exists('priority', $tag)) {
                    $priority = 0 - $tag['priority'];
                }
            }
            $priorities[] = $priority;
        }

        return $priorities;
    }
}
