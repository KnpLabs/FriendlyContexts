<?php

namespace Knp\FriendlyExtension\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RemoveUnavailableServicesPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $this->removeDependendiesFromContainer($container);
    }

    private function removeDependendiesFromContainer(ContainerBuilder $container, $id = null)
    {
        foreach ($container->findTaggedServiceIds('remove-when-missing') as $id => $tags) {
            foreach ($tags as $tag) {
                if (!isset($tag['service'])) {
                    continue;
                }

                if (null !== $id && $id === $tag['service']) {
                    continue;
                }

                if ($container->hasDefinition($tag['service'])) {
                    continue;
                }

                if ($container->hasAlias($tag['service'])) {
                    continue;
                }

                $container->removeDefinition($id);

                $this->removeDependendiesFromContainer($container, $id);
                $this->removeFromEventDispatcher($container, $id);
            }
        }
    }

    private function removeFromEventDispatcher(ContainerBuilder $container, $id)
    {
        $dispatcher = $container->getDefinition('event_dispatcher');
        $calls      = $dispatcher->getMethodCalls();

        foreach ($calls as $index => $call) {
            list($method, $arguments) = $call;

            if (false === in_array($method, [ 'addSubscriber', 'addListener' ])) {

                continue;
            }

            if ($id !== (string)current($arguments)) {

                continue;
            }

            unset($calls[$index]);
        }

        $dispatcher->setMethodCalls($calls);
    }
}
