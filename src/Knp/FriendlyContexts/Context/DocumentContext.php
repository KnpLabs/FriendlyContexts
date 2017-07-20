<?php

namespace Knp\FriendlyContexts\Context;

use Doctrine\ODM\MongoDB\SchemaManager;

class DocumentContext extends Context
{
    /**
     * @BeforeScenario
     */
    public function beforeScenario($event)
    {
        $this->storeTags($event);

        if ($this->hasTags([ 'reset-collections', '~not-reset-collections' ])) {
            $dm = $this->getEntityManager();
            $scm = new SchemaManager($dm, $dm->getMetadataFactory());

            $scm->dropCollections();
        }
    }
}
