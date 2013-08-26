<?php

namespace Knp\FriendlyContexts\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;

use Knp\FriendlyContexts\Reflection\ObjectReflector;
use Doctrine\Common\Util\Inflector;

class EntityResolver
{
    protected $entityManager;
    protected $reflector;

    public function __construct(ObjectManager $entityManager, ObjectReflector $reflector = null)
    {
        $this->entityManager = $entityManager;
        $this->reflector     = null !== $reflector ? $reflector : new ObjectReflector();
    }

    public function resolve($name, $namespaces)
    {
        if (is_string($namespaces)) {
            $namespaces = [$namespaces];
        }

        $allClass = $this
            ->reflector
            ->getReflectionsFromMetadata(
                $this
                    ->entityManager
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

    public function getMetadataFromObject($object)
    {
        return $this
            ->entityManager
            ->getMetadataFactory()
            ->getMetadataFor(get_class($object)
        );
    }
}
