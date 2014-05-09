<?php

namespace Knp\FriendlyContexts\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ApiUrlPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $url = $container->getParameter('friendly.api.base_url');

        if (empty($url)) {
            if ($container->hasParameter('mink.base_url')) {
                $url = $container->getParameter('mink.base_url');
            }
        }

        $container->setParameter('friendly.api.base_url', $url);
    }
}
