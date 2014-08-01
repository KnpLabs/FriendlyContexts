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
            $this->buildEntity($name, $values);
        }

        $this->get('doctrine')->flush();
    }

    /**
     * @Given there is :count :name
     * @Given there is :count :name like:
     */
    public function thereIsEntities($count, $name, TableNode $table = null)
    {
        $data = $table ? $table->getColumnsHash() : [[]];

        for ($i = 0; $i < $count; $i++) {
            $values = $data[$i % count($data)];
            $this->buildEntity($name, $values);
        }

        $this->get('doctrine')->flush();
    }

    /**
     * @Then :count :name should have been :state
     */
    public function entitiesShoudhaveBeen($count, $name, $state)
    {
        $diff = $this->get('entity')->getDiff($name);
        $count = is_numeric($count) ? intval($count) : 0;

        if (false === array_key_exists($state, $diff)) {

            throw new \Exception(sprintf('Don\'t know how to get %s %s', $state, $name));
        }

        $message = sprintf('%s %s have been %s, %s expected', count($diff[$state]), $name, $state, $count);
        $this->get('asserter')->assertEquals($count, count($diff[$state]), $message);
    }

    public function buildEntity($name, array $values)
    {
        $entity = $this->get('entity')->createNew($name);
        $this->get('entity')->hydrate($entity, $values);
        $this->get('entity')->complete($entity);
        $this->get('doctrine')->persist($entity);
        $this->get('record')->attach($entity, $values);

        return $entity;
    }
}
