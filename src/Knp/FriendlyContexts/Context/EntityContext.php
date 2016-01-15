<?php

namespace Knp\FriendlyContexts\Context;

use Behat\Gherkin\Node\TableNode;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Sharding\PoolingShardConnection;
use Doctrine\DBAL\Sharding\PoolingShardManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Component\PropertyAccess\PropertyAccess;

class EntityContext extends Context
{
    /**
     * @Given /^the following ([\w ]+):?$/
     */
    public function theFollowing($name, TableNode $table)
    {
        $entityName = $this->resolveEntity($name)->getName();

        $rows = $table->getRows();
        $headers = array_shift($rows);

        foreach ($rows as $row) {
            $values     = array_combine($headers, $row);
            $entity     = new $entityName;
            $reflection = new \ReflectionClass($entity);

            do {
                $this
                    ->getRecordBag()
                    ->getCollection($reflection->getName())
                    ->attach($entity, $values)
                ;
                $reflection = $reflection->getParentClass();
            } while (false !== $reflection);

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
     * @Given /^there (?:is|are) (\d+) ((?!\w* like)\w*)$/
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
     * @Given /^there (?:is|are) (\d+) (.*) like:?$/
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
            ->resetDQLParts()
            ->select('o')
            ->from($entityName, ' o')
            ->getQuery()
            ->getResult()
        ;

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
     * @Then /^should be (\d+) (.*) like:?$/
     */
    public function existLikeFollowing($nbr, $name, TableNode $table)
    {
        $entityName = $this->resolveEntity($name)->getName();

        $rows = $table->getRows();
        $headers = array_shift($rows);

        $accessor = PropertyAccess::createPropertyAccessor();

        for ($i = 0; $i < $nbr; $i++) {
            $row = $rows[$i % count($rows)];

            $values = array_combine($headers, $row);
            $object = $this->getEntityManager()
                ->getRepository($entityName)
                ->findOneBy(
                    $this->getEntityIdentifiers($entityName, $headers, $row)
                );

            if (is_null($object)) {
                throw new \Exception(sprintf("There is not any object for the following identifiers: %s", json_encode($this->getEntityIdentifiers($entityName, $headers, $row))));
            }
            $this->getEntityManager()->refresh($object);

            foreach ($values as $key => $value) {
                if ($value != $accessor->getValue($object, $key) ) {
                    throw new \Exception("The expected object does not have property $key with value $value");
                }
            }
        }
    }

    /**
     * @BeforeScenario
     */
    public function beforeScenario($event)
    {
        $this->storeTags($event);

        if ($this->hasTags([ 'reset-schema', '~not-reset-schema' ])) {
            foreach ($this->getEntityManagers() as $name => $entityManager) {
                $connection = $entityManager->getConnection();
                if ($connection instanceof PoolingShardConnection && $this->hasTag('reset-shard-schema')) {
                    $poolingShardManager = $this->getPoolingShardManager($connection);
                    foreach ($poolingShardManager->getShards() as $shardId) {
                        // Switch to shard database
                        $connection->connect($shardId['id']);
                        $this->resetSchema($entityManager);
                    }
                    // Back to global
                    $connection->connect(0);
                }
                $this->resetSchema($entityManager);
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

    /**
     * @param EntityManager $entityManager
     */
    protected function resetSchema(EntityManager $entityManager)
    {
        $metadata = $this->getMetadata($entityManager);
        if (!empty($metadata)) {
            $tool = new SchemaTool($entityManager);
            $tool->dropSchema($metadata);
            $tool->createSchema($metadata);
        }
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

    /**
     * Find PoolingShardManager related to connection.
     *
     * Cannot directly find PoolingShardManager by EntityManager's name cause
     * PoolingShardManager is created from Connection's name.
     *
     * @param PoolingShardConnection $poolingShardConnection
     *
     * @return PoolingShardManager
     *
     * @throws \LogicException Unable to find PoolingShardManager related to this PoolingShardConnection.
     */
    protected function getPoolingShardManager(PoolingShardConnection $poolingShardConnection)
    {
        foreach ($this->getConnections() as $name => $connection) {
            if ($connection === $poolingShardConnection
                && $this->getKernel()->getContainer()->has(sprintf('doctrine.dbal.%s_shard_manager', $name))
            ) {
                return $this->get(sprintf('doctrine.dbal.%s_shard_manager', $name));
            }
        }
        throw new \LogicException('Unable to find PoolingShardManager related to this PoolingShardConnection.');
    }

    /**
     * @param EntityManager $entityManager
     *
     * @return ClassMetadata[]
     */
    protected function getMetadata(EntityManager $entityManager)
    {
        return $entityManager->getMetadataFactory()->getAllMetadata();
    }

    /**
     * @return EntityManager[]
     */
    protected function getEntityManagers()
    {
        return $this->get('doctrine')->getManagers();
    }

    /**
     * @return Connection[]
     */
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

    /**
     * @param string $entityName
     * @param array $headers Headers of the Behat TableNode
     * @param array $row current row if tge Behat TableNode
     * @return array ['id_column_A' => 'value', 'id_column_B' => 'value']
     * @throws \Exception
     */
    protected function getEntityIdentifiers($entityName, $headers, $row)
    {
        $metadata = $this->getEntityManager()->getClassMetadata($entityName);
        $identifiers = $metadata->getIdentifierFieldNames();

        $identifiersWithValues = [];

        foreach ($identifiers as $identifier) {
            $headersPosition = array_search($identifier, $headers);
            $identifiersWithValues[$identifier] = $row[$headersPosition];
        }

        return $identifiersWithValues;
    }
}

