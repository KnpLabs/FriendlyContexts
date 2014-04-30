<?php

namespace Knp\FriendlyContexts\Context;

use Behat\Gherkin\Node\TableNode;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;

class EntityContext extends Context
{
    /**
     * @Given /^the following (\w*):?$/
     */
    public function theFollowing($name, TableNode $table)
    {
        $entityName = $this->resolveEntity($name)->getName();

        $rows = $table->getRows();
        $headers = array_shift($rows);

        foreach ($rows as $row) {
            $values = array_combine($headers, $row);
            $entity = new $entityName;
            $this
                ->getRecordBag()
                ->getCollection($entityName)
                ->attach($entity, $values)
            ;
            $this
                ->getEntityHydrator()
                ->hydrate($this->getEntityManager(), $entity, $values)
                ->completeRequired($this->getEntityManager(), $entity)
            ;

            $this->getEntityManager()->persist($entity);
        }

        $this->getEntityManager()->flush();
    }

    /**
     * @Given /^there is (\d+) ((?!\w* like)\w*)$/
     */
    public function thereIs($nbr, $name)
    {
        $entityName = $this->resolveEntity($name)->getName();

        for ($i = 0; $i < $nbr; $i++) {
            $entity = new $entityName;
            $this
                ->getRecordBag()
                ->getCollection($entityName)
                ->attach($entity)
            ;
            $this
                ->getEntityHydrator()
                ->completeRequired($this->getEntityManager(), $entity)
            ;

            $this->getEntityManager()->persist($entity);
        }

        $this->getEntityManager()->flush();
    }

    /**
     * @Given /^there is (\d+) (.*) like:?$/
     */
    public function thereIsLikeFollowing($nbr, $name, TableNode $table)
    {
        $entityName = $this->resolveEntity($name)->getName();

        $rows = $table->getRows();
        $headers = array_shift($rows);

        for ($i = 0; $i < $nbr; $i++) {
            $row = $rows[$i % count($rows)];
            $values = array_combine($headers, $row);
            $entity = new $entityName;
            $this
                ->getRecordBag()
                ->getCollection($entityName)
                ->attach($entity, $values)
            ;
            $this
                ->getEntityHydrator()
                ->hydrate($this->getEntityManager(), $entity, $values)
                ->completeRequired($this->getEntityManager(), $entity)
            ;

            $this->getEntityManager()->persist($entity);
        }

        $this->getEntityManager()->flush();
    }

    /**
     * @Given /^(\w+) (.+) should have been (created|deleted)$/
     */
    public function entitiesShouldHaveBeen($expected, $entity, $state)
    {
        $expected = (int) $expected;

        $entityName = $this->resolveEntity($entity)->getName();
        $collection = $this
            ->getRecordBag()
            ->getCollection($entityName)
        ;

        $records = array_map(function ($e) { return $e->getEntity(); }, $collection->all());
        $entities = $this
            ->getEntityManager()
            ->getRepository($entityName)
            ->createQueryBuilder('o')
            ->getQuery()
            ->getResult()
        ;

        $diff = [];
        if ($state === 'created') {
            $diff = $this->compareArray($entities, $records);
            foreach ($diff as $e) {
                $collection->attach($e);
            }
        } else {
            $diff = $this->compareArray($records, $entities);
        }
        $real = count($diff);

        $this
            ->getAsserter()
            ->assertEquals(
                $real,
                $expected,
                sprintf('%s %s should have been %s, %s actually', $expected, $entity, $state, $real)
            )
        ;
    }

    /**
     * @BeforeScenario
     */
    public function beforeScenario($event)
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
     * @AfterScenario
     */
    public function afterScenario($event)
    {
        $this->getRecordBag()->clear();
        $this->getUniqueCache()->clear();
        $this->getEntityManager()->clear();
    }

    protected function compareArray(array $a1, array $a2)
    {
        $diff = [];
        foreach ($a1 as $e) {
            if (!in_array($e, $a2)) {
                $diff[] = $e;
            }
        }

        return $diff;
    }

    protected function getMetadata(EntityManager $entityManager)
    {
        return $entityManager->getMetadataFactory()->getAllMetadata();
    }

    protected function getEntityManagers()
    {
        return $this->get('doctrine')->getManagers();
    }

    protected function getConnections()
    {
        return $this->get('doctrine')->getConnections();
    }

    protected function getDefaultOptions()
    {
        return [
            'Entities' => [''],
        ];
    }
}
