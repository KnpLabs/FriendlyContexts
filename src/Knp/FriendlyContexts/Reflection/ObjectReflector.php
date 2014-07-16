<?php

namespace Knp\FriendlyContexts\Reflection;

class ObjectReflector
{
    public function getReflectionClass($object)
    {
        return new \ReflectionClass($object);
    }

    public function getClassName($object)
    {
        return $this->getReflectionClass($object)->getShortName();
    }

    public function getClassNamespace($object)
    {
        return $this->getReflectionClass($object)->getNamespaceName();
    }

    public function getClassLongName($object)
    {
        return sprintf(
            "%s\\%s",
            $this->getClassNamespace($object),
            $this->getClassName($object)
        );
    }

    public function isInstanceOf($object, $class)
    {
        return $object instanceof $class || $this->getClassLongName($object) === $class;
    }

    public function getReflectionsFromMetadata($metadata)
    {
        return array_map(
            function ($e) {
                return $this->getReflectionClass($e->name);
            },
            $metadata
        );
    }
}
