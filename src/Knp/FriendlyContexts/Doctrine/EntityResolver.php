<?php

namespace Knp\FriendlyContexts\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Inflector\Inflector;
use Knp\FriendlyContexts\Reflection\ObjectReflector;
use Knp\FriendlyContexts\FacadeProvider;
use Knp\FriendlyContexts\Dictionary\FacadableInterface;
use Knp\FriendlyContexts\Dictionary\Facadable;

class EntityResolver implements FacadableInterface
{
    use Facadable;

    public function resolve(ObjectManager $entityManager, $name, $namespaces)
    {
        if (is_string($namespaces)) {
            $namespaces = [$namespaces];
        }

        $allClass = $this
            ->getDeps('object.reflector')
            ->getReflectionsFromMetadata(
                $entityManager
                    ->getMetadataFactory()
                    ->getAllMetadata()
        );

        $names = $this->entityNameProposal($name);

        $results = [];

        foreach ($namespaces as $namespace) {
            foreach ($names as $name) {
                $class = array_filter(
                    $allClass,
                    function ($e) use ($namespace, $name) {
                        $nameValid = strtolower($e->getShortName()) === strtolower($name);
                        return '' === $namespace
                            ? $nameValid
                            : $namespace === substr($e->getNamespaceName(), 0, strlen($namespace)) && $nameValid
                        ;
                    }
                );
                foreach ($class as $c) {
                    if (!in_array($c, $results)) {
                        $results[] = $c;
                    }
                }
            }
            if (0 < count($results)) {

                return $results;
            }
        }

        return $results;
    }

    public function entityNameProposal($name)
    {
        $name = strtolower(str_replace(" ", "", $name));

        $results = [Inflector::singularize($name), Inflector::pluralize($name), $name];

        return array_unique($results);
    }

    public function getMetadataFromObject(ObjectManager $entityManager, $object)
    {
        return $entityManager
            ->getMetadataFactory()
            ->getMetadataFor(get_class($object)
        );
    }
}
