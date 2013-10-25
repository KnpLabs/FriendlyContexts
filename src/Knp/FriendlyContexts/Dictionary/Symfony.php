<?php

namespace Knp\FriendlyContexts\Dictionary;

use Behat\Symfony2Extension\Context\KernelDictionary;
use Symfony\Component\DependencyInjection\ContainerInterface;

trait Symfony
{
    use KernelDictionary;

    protected $container;

    protected function getContainer()
    {
        return $this->container ?: $this->getKernel()->getContainer();
    }

    protected function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    protected function get($name)
    {
        return $this->getContainer()->get($name);
    }

    protected function getEntityManager()
    {
        return $this->getContainer()->get('doctrine')->getManager();
    }

    protected function getProfiler()
    {
        return $this->getContainer()->get('profiler');
    }

    protected function getRepository($entity)
    {
        return $this->getEntityManager()->getRepository($entity);
    }
}
