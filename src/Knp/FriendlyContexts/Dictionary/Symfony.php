<?php

namespace Knp\FriendlyContexts\Dictionary;

use Behat\Symfony2Extension\Context\KernelDictionary;

trait Symfony
{
    use KernelDictionary;

    protected function get($name)
    {
        return $this->getContainer()->get($name);
    }

    protected function generateUrl($route, array $parameters = array(), $absolute = false)
    {
        return $this->getContainer()->get('router')->generate($route, $parameters, $absolute);
    }

    protected function getSecurityContext()
    {
        return $this->getContainer()->get('security.context');
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

    protected function getMessagesLogger()
    {
        return $this->getContainer()->get('knp_rad.mailer.logger');
    }

    protected function getUser()
    {
        $token = $this->getSecurityContext()->getToken();

        if (null === $token) {
            throw new \Exception('No token found in security context.');
        }

        return $token->getUser();
    }

    protected function persist($entity)
    {
        $this->getEntityManager()->persist($entity);
    }

    protected function persistAndFlush($entity)
    {
        $this->persist($entity);
        $this->getEntityManager()->flush($entity);
    }

    protected function flush()
    {
        $this->getEntityManager()->flush();
    }

}
