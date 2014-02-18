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
        }

        foreach ($files as $name) {
            if (!array_key_exists($name, $fixtures)) {

                throw new \Exception(sprintf('Fixture "%s" unknown. "%s" expected', $name, implode('", "', array_keys($fixtures))));
            }
        }

        $result = [];
        foreach ($fixtures as $id => $fixture) {
            if (in_array($id, $files)) {
                foreach ($loader->load($fixture) as $entity) {
                    $this->getEntityManager()->persist($entity);
                }
                $this->getEntityManager()->flush();
                foreach ($loader->getCache() as list($data, $entity)) {
                    $this
                        ->getRecordBag()
                        ->getCollection(get_class($entity))
                        ->attach($entity, $data)
                    ;
                }
                $loader->clearCache();
            }
        }
    }
}
