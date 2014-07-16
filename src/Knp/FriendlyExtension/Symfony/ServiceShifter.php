<?php

namespace Knp\FriendlyExtension\Symfony;

use Symfony\Component\DependencyInjection\ContainerInterface;

class ServiceShifter
{
    public function __construct(ContainerInterface $container)
    {
        $this->kernel = $container->get('friendly.symfony.kernel', ContainerInterface::NULL_ON_INVALID_REFERENCE);
    }

    public function get($id)
    {
        if (null === $this->kernel) {

            return null;
        }

        return $this
            ->kernel
            ->getContainer()
            ->get($id, ContainerInterface::NULL_ON_INVALID_REFERENCE)
        ;
    }
}
