<?php

namespace Knp\FriendlyContexts\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;

use Knp\FriendlyContexts\Reflection\ObjectReflector;

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
                            : 0 === strpos($e->getNamespaceName(), $namespace) && $nameValid
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
        var_dump($results);

        return $results;
    }

    public function entityNameProposal($name)
    {
        $name = strtolower(str_replace(" ", "", $name));

        $results = [$name];

        if ('s' === substr($name, -1)) {
            $results[] = substr($name, 0, -1);
        }

        if ('ies' === substr($name, -3)) {
            $results[] = substr($name, 0, -3).'y';
        }

        return $results;
    }
}
