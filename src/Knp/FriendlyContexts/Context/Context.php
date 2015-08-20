<?php

namespace Knp\FriendlyContexts\Context;

use Behat\Behat\Context\Context as ContextInterface;
use Knp\FriendlyContexts\Dictionary\Backgroundable;
use Knp\FriendlyContexts\Dictionary\Taggable;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

abstract class Context implements ContextInterface
{
    use Backgroundable,
        Taggable;

    protected $config = [];
    protected $container;

    public function initialize(array $config, ContainerInterface $container)
    {
        $this->config    = array_merge($this->getDefaultOptions(), $config);
        $this->container = $container;
    }

    protected function getRecordBag()
    {
        return $this->get('friendly.record.bag');
    }

    protected function getEntityHydrator()
    {
        return $this->get('friendly.entity.hydrator');
    }

    protected function getEntityResolver()
    {
        return $this->get('friendly.entity.resolver');
    }

    protected function getTextFormater()
    {
        return $this->get('friendly.text.formater');
    }

    protected function getAsserter()
    {
        return $this->get('friendly.asserter');
    }

    protected function getGuesserManager()
    {
        return $this->get('friendly.guesser.manager');
    }

    protected function getObjectReflector()
    {
        return $this->get('friendly.object.reflector');
    }

    protected function getFeatureWalker()
    {
        return $this->get('friendly.feature.walker');
    }

    protected function getAliceLoader()
    {
        return $this->get('friendly.alice.fixtures.loader');
    }

    protected function getEntityManager()
    {
        return $this->get('doctrine')->getManager();
    }

    protected function getUniqueCache()
    {
        return $this->get('friendly.unique_cache');
    }

    protected function getPageClassResolver()
    {
        return $this->get('friendly.page.resolver');
    }

    protected function getRequestBuilder()
    {
        return $this->get('friendly.builder.request_builder');
    }

    protected function getHttpContentTypeGuesser()
    {
        return $this->get('friendly.http.http_content_type_guesser');
    }

    protected function get($service)
    {
        if ($this->container->has($service)) {
            return $this->container->get($service);
        }

        if (null !== $this->getKernel() && $this->getKernel()->getContainer()->has($service)) {
            return $this->getKernel()->getContainer()->get($service);
        }

        throw new ServiceNotFoundException($service);
    }

    protected function getParameter($name)
    {
        if ($this->container->hasParameter($name)) {
            return $this->container->getParameter($name);
        }

        if (null !== $this->getKernel() && $this->getKernel()->getContainer()->hasParameter($name)) {
            return $this->getKernel()->getContainer()->getParameter($name);
        }

        throw new ParameterNotFoundException($name);
    }

    protected function getKernel()
    {
        if ($this->container->has('friendly.symfony.kernel')) {
            $kernel = $this->container->get('friendly.symfony.kernel');
            $kernel->boot();

            return $kernel;
        }
    }

    protected function resolveEntity($name)
    {
        $namespaces = $this->getParameter('friendly.entities.namespaces');

        $entities = $this
            ->getEntityResolver()
            ->resolve(
                $this->getEntityManager(),
                $name,
                empty($namespaces) ? '' : $namespaces
            )
        ;

        switch (true) {
            case 1 < count($entities):
                throw new \Exception(
                    sprintf(
                        'Failed to find a unique model from the name "%s", "%s" found',
                        $name,
                        implode('" and "', array_map(
                            function ($rfl) {
                                return $rfl->getName();
                            },
                            $entities
                        ))
                    )
                );
                break;
            case 0 === count($entities):
                throw new \Exception(
                    sprintf(
                        'Failed to find a model from the name "%s"',
                        $name
                    )
                );
        }

        return current($entities);
    }

    protected function getDefaultOptions()
    {
        return [];
    }
}
