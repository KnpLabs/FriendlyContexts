<?php

namespace Knp\FriendlyContexts\Dictionary;

use Behat\Symfony2Extension\Context\KernelDictionary;
use Symfony\Component\DependencyInjection\ContainerInterface;

trait Symfony
{
    use KernelDictionary;

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
