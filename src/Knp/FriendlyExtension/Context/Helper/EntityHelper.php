<?php

namespace Knp\FriendlyExtension\Context\Helper;

use Doctrine\ORM\EntityManagerInterface;
use Knp\FriendlyExtension\Context\Helper\AbstractHelper;
use Knp\FriendlyExtension\Doctrine\Resolver;
use Knp\FriendlyExtension\Faker\UniqueCache;
use Knp\FriendlyExtension\Record\Collection\Bag;
use Knp\FriendlyExtension\Type\GuesserRegistry;
use Symfony\Component\PropertyAccess\PropertyAccess;

class EntityHelper extends AbstractHelper
{
    private $bag;

    public function __construct(Resolver $resolver, Bag $bag, UniqueCache $cache, GuesserRegistry $registry)
    {
        $this->resolver       = $resolver;
        $this->bag            = $bag;
        $this->cache          = $cache;
        $this->registry       = $registry;
    }

    public function getName()
    {
        return 'entity';
    }

    public function clear()
    {
        $this->cache->clear();
        $this->bag->clear();
    }

    public function createNew($name)
    {
        $class = $this->get('doctrine')->getClass($name);

        return (new \ReflectionClass($class))->newInstanceWithoutConstructor();
    }

    public function hydrate($entity, array $values)
    {
        foreach ($values as $property => $value) {
            $mapping = $this->resolver->getMetadataFor($entity, $property);

            PropertyAccess::getPropertyAccessor()
                ->setValue(
                    $entity,
                    $mapping['fieldName'],
                    $this->format($mapping, $value)
                )
            ;
        }
    }

    public function complete($entity)
    {
        $metadata = array_merge(
            $this->resolver->getMetadataFor($entity)->fieldMappings,
            $this->resolver->getMetadataFor($entity)->associationMappings
        );
        $accessor = PropertyAccess::getPropertyAccessor();

        foreach ($metadata as $property => $mapping) {
            if (false === $metadata->isNullable($property) && null === $accessor->getValue($entity, $property)) {
                $accessor ->setValue(
                    $entity,
                    $mapping['fieldName'],
                    $this->fake($mapping)
                );
            }
        }
    }

    public function getDiff($name)
    {
        $class      = $this->get('doctrine')->getClass($name);
        $existing   = $this->get('doctrine')->all($class);
        $collection = $this->bag->getCollection($class);
        $registered = array_map(function ($e) { return $e->getEntity(); }, $collection->all());

        return [
            'created' => array_diff($existing, $registered),
            'deleted' => array_diff($registered, $existing),
        ];
    }

    private function format(array $mapping, $value)
    {
        if (false === $guesser = $this->registry->find($mapping)) {

            return $value;
        }

        return $guesser->transform($value, $mapping);
    }

    private function fake($mapping)
    {
        if (false === $guesser = $this->registry->find($mapping)) {
            throw new \Exception(sprintf('There is no fake solution for "%s" typed fields', $mapping['type']));
        }

        if (true === $mapping['unique']) {
            return $this->cache->generate($className, $mapping['fieldName'], function () use ($guesser, $mapping) {
                return $guesser->fake($mapping);
            });
        }
    }
}
