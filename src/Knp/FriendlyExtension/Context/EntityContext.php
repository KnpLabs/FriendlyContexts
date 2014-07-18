<?php

namespace Knp\FriendlyExtension\Context;

use Behat\Gherkin\Node\TableNode;
use Knp\FriendlyExtension\Context\Context;

class EntityContext extends Context
{
    /**
     * @Given the following :name:
     */
    public function theFollowingEntities($name, TableNode $table)
    {
        $data = $table->getColumnsHash();

        foreach ($data as $values) {
            $entity = $this->get('entity')->createNew($name);
            $this->get('entity')->hydrate($entity, $values);
            $this->get('entity')->complete($entity);
            $this->get('doctrine')->persist($entity);
        }

        $this->get('doctrine')->flush();
    }
}
