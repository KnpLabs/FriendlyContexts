<?php

namespace Knp\FriendlyExtension\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class FakerGuesserRegistrationPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $guessers = array_keys($container->findTaggedServiceIds('friendly.type.guesser'));
        $fakers   = array_keys($container->findTaggedServiceIds('friendly.faker.provider'));

        $regitry = $container->getDefinition('friendly.type.guesser_registry');

        foreach ($guessers as $guesser) {
            $definition = $container->getDefinition($guesser);
            foreach ($fakers as $faker) {
                $definition->addMethodCall('addFaker', [ new Reference($faker) ]);
            }
            $regitry->addMethodCall('addGuesser', [ new Reference($guesser) ]);
        }
    }
}
