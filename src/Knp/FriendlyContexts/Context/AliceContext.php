<?php

namespace Knp\FriendlyContexts\Context;

class AliceContext extends Context
{
    /**
     * @BeforeBackground
     **/
    public function loadAlice($event)
    {
        $this->storeTags($event);
        $fixtures = $this->getParameter('friendly.alice.fixtures');
        $loader = $this->getAliceLoader();

        $files = $this->getTagContent('alice');

        if (in_array('*', $files)) {
            $files = array_keys($fixtures);
        } else {
            $files = $this->resolveDepsFromArray($files);
        }

        foreach ($files as $name) {
            if (!array_key_exists($name, $fixtures)) {

                throw new \Exception(sprintf('Fixture "%s" unknown. "%s" expected', $name, implode('", "', array_keys($fixtures))));
            }
        }

        $this->loadFixtures($loader, $fixtures, $files);
        $this->registerCache($loader);
    }

    protected function loadFixtures($loader, $fixtures, $files)
    {
        $persistable = $this->getPersistableClasses();

        foreach ($fixtures as $id => $fixture) {
            if (in_array($id, $files)) {
                foreach ($loader->load($fixture) as $object) {
                    if (in_array(get_class($object), $persistable)) {
                        $this->getEntityManager()->persist($object);
                    }
                }

                $this->getEntityManager()->flush();
            }
        }
    }

    private function getPersistableClasses()
    {
        $persistable = array();
        $metadatas   = $this->getEntityManager()->getMetadataFactory()->getAllMetadata();

        foreach ($metadatas as $metadata) {
            if (isset($metadata->isEmbeddedClass) && $metadata->isEmbeddedClass) {
                continue;
            }

            $persistable[] = $metadata->getName();
        }

        return $persistable;
    }

    protected function registerCache($loader)
    {
        foreach ($loader->getCache() as $cache) {
            list($values, $entity) = $cache;
            $reflection = new \ReflectionClass($entity);
            do {
                $this
                    ->getRecordBag()
                    ->getCollection($reflection->getName())
                    ->attach($entity, $values)
                ;
                $reflection = $reflection->getParentClass();
            } while (false !== $reflection);
        }
        $loader->clearCache();
    }

    protected function resolveDepsFromArray(array $fixtures)
    {
        $result = [];

        foreach ($fixtures as $fixture) {
            $this->resolveDeps($fixture, $result);
        }

        return $result;
    }

    protected function resolveDeps($fixture, array &$result = [])
    {
        $result[] = $fixture;
        $tree = $this->getParameter('friendly.alice.dependencies');

        if (!empty($tree[$fixture])) {
            foreach ($tree[$fixture] as $dep) {
                if (!in_array($dep, $result)) {
                    $this->resolveDeps($dep, $result);
                }
            }
        }

        return $result;
    }
}
