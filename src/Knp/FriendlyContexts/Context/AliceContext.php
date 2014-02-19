<?php

namespace Knp\FriendlyContexts\Context;

use Knp\FriendlyContexts\Alice\Loader\Yaml;

class AliceContext extends Context
{
    /**
     * @BeforeBackground
     **/
    public function loadAlice($event)
    {
        $this->storeTags($event);
        $fixtures = $this->config['alice']['fixtures'];
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
        foreach ($fixtures as $id => $fixture) {
            if (in_array($id, $files)) {
                foreach ($loader->load($fixture) as $entity) {
                    $this->getEntityManager()->persist($entity);
                }
                $this->getEntityManager()->flush();
            }
        }
    }

    protected function registerCache($loader)
    {
        foreach ($loader->getCache() as $cache) {
            list($data, $entity) = $cache;
            $this
                ->getRecordBag()
                ->getCollection(get_class($entity))
                ->attach($entity, $data)
                ;
        }
        $loader->clearCache();
    }

    protected function resolveDepsFromArray(array $fixtures)
    {
        $result = [];

        foreach ($fixtures as $fixture) {
            $result = array_merge($result, $this->resolveDeps($fixture));
        }

        return $result;
    }

    protected function resolveDeps($fixture)
    {
        $result = [ $fixture ];
        $tree = $this->config['alice']['dependencies'];

        if (!empty($tree[$fixture])) {
            foreach ($tree[$fixture] as $dep) {
                $result = array_merge($result, $this->resolveDeps($dep));
            }
        }

        return $result;
    }
}
