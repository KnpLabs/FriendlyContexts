<?php

namespace Knp\FriendlyContexts\Context;

use Behat\Gherkin\Node\TableNode;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Knp\FriendlyContexts\Doctrine\EntityResolver;
use Knp\FriendlyContexts\Reflection\ObjectReflector;
use Knp\FriendlyContexts\Record\Collection\Bag;
use Knp\FriendlyContexts\Record\Record;
use Knp\FriendlyContexts\FacadeProvider;

class EntityContext extends BehatContext
{
    /**
     * @Given /^the following (.*)$/
     */
    public function theFollowing($name, TableNode $table)
    {
        $entityName = $this->resolveEntity($name)->getName();

        $rows = $table->getRows();
        $headers = array_shift($rows);

        foreach ($rows as $row) {
            $values = array_combine($headers, $row);
            $entity = new $entityName;
            $record = $this->getDeps('record.bag')->get($entityName)->attach($entity, $values);

            foreach ($values as $property => $value) {
                $mapping = $this->resolveProperty($record, $property, $value);
                if (!array_key_exists('isOwningSide', $mapping)) {
                    switch ($mapping['type']) {
                        case 'array':
                            $value = $this->listToArray($value);
                        default:
                            PropertyAccess::getPropertyAccessor()->setValue($entity, $mapping['fieldName'], $value);
                            break;
                    }
                } else {
                    $targetEntity = $mapping['targetEntity'];
                    if (null === $entityCollection = $this->getDeps('record.bag')->get($targetEntity)) {
                        throw new \Exception(sprintf("Can't find collection for %s", $targetEntity));
                    }

                    if (in_array($mapping['type'], [ClassMetadata::ONE_TO_MANY, ClassMetadata::MANY_TO_MANY])) {
                        $records = new ArrayCollection;
                        foreach ($this->listToArray($value) as $v) {
                            if (null === $targetRecord = $entityCollection->search($v)) {
                                throw new \Exception(sprintf("Can't find %s with value %s", $targetEntity, $v));
                            }
                            $records->add($targetRecord->getEntity());
                        }
                        PropertyAccess::getPropertyAccessor()->setValue($entity, $mapping['fieldName'], $records);
                    } else {
                        if (null === $targetRecord = $entityCollection->search($value)) {
                            throw new \Exception(sprintf("Can't find %s with value %s", $targetEntity, $value));
                        }
                        PropertyAccess::getPropertyAccessor()->setValue($entity, $mapping['fieldName'], $targetRecord->getEntity());
                    }
                }
            }
            $this->getEntityManager()->persist($entity);
        }

        $this->getEntityManager()->flush();
    }

    /**
     * @Given /^(\w+) (.+) should be created$/
     */
    public function entitiesShouldBeCreated($expected, $entity)
    {
        $expected = (int) $expected;

        $entityName = $this->resolveEntity($entity)->getName();
        $collection = $this->getDeps('record.bag')->get($entityName);

        $entities = $this->getEntityManager()->getRepository($entityName)->findAll();

        $real =(count($entities) - $collection->count());
        $real = $real > 0 ? $real : 0;

        $this->assertEquals(
            $real,
            $expected,
            sprintf('%s %s should be created, %s in reality', $expected, $entity, $real)
        );
    }

    /**
     * @Given /^(\w+) (.+) should be deleted$/
     */
    public function entitiesShouldBeDeleted($expected, $entity)
    {
        $expected = (int) $expected;

        $entityName = $this->resolveEntity($entity)->getName();
        $collection = $this->getDeps('record.bag')->get($entityName);

        $entities = $this->getEntityManager()->getRepository($entityName)->findAll();

        $real = ($collection->count() - count($entities));
        $real = $real > 0 ? $real : 0;

        $this->assertEquals(
            $real,
            $expected,
            sprintf('%s %s should be deleted, %s in reality', $expected, $entity, $real)
        );
    }

    /**
     * @BeforeScenario
     */
    public function buildSchema($event)
    {
        $this->storeTags($event);

        if ($this->hasTags([ 'reset-schema', '~not-reset-schema' ])) {
            foreach ($this->getEntityManagers() as $entityManager) {
                $metadata = $this->getMetadata($entityManager);

                if (!empty($metadata)) {
                    $tool = new SchemaTool($entityManager);
                    $tool->dropSchema($metadata);
                    $tool->createSchema($metadata);
                }
            }
        }
    }

    /**
     * @AfterBackground
     */
    public function clearManager($event)
    {
        $this->getEntityManager()->clear();
    }

    protected function resolveEntity($name)
    {
        $entities = $this->getDeps('entity.resolver')->resolve($this->getEntityManager(), $name, $this->options['Entities']);

        switch (true) {
            case 1 < count($entities):
                throw new \Exception(
                    sprintf(
                        'Fail to find a unique model from the name "%s", "%s" found',
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
                        'Fail to find a model from the name "%s"',
                        $name
                    )
                );
                break;
        }
        return current($entities);
    }

    protected function resolveProperty(Record $record, $property, $value)
    {
        $metadata     = $this->getDeps('entity.resolver')->getMetadataFromObject($this->getEntityManager(), $record->getEntity());
        $fields       = $metadata->fieldMappings;
        $associations = $metadata->associationMappings;

        foreach ($fields as $id => $map) {
            switch (strtolower($id)) {
                case strtolower($property):
                case $this->toCamelCase(strtolower($property)):
                case $this->toUnderscoreCase(strtolower($property)):
                    return $map;
            }
        }

        foreach ($associations as $id => $map) {
            switch (strtolower($id)) {
                case strtolower($property):
                case $this->toCamelCase(strtolower($property)):
                case $this->toUnderscoreCase(strtolower($property)):
                    return $map;
            }
        }

        throw new \RuntimeException(
            sprintf(
                'Can\'t find property %s or %s in class %s',
                $this->toCamelCase(strtolower($property)),
                $this->toUnderscoreCase(strtolower($property)),
                get_class($record->getEntity())
            )
        );
    }

    protected function getMetadata(EntityManager $entityManager)
    {
        return $entityManager->getMetadataFactory()->getAllMetadata();
    }

    protected function getEntityManagers()
    {
        return $this->getContainer()->get('doctrine')->getManagers();
    }

    protected function getConnections()
    {
        return $this->getContainer()->get('doctrine')->getConnections();
    }

    protected function getDefaultOptions()
    {
        return [
            'Entities' => [''],
        ];
    }
}
