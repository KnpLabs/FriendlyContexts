<?php

namespace Knp\FriendlyContexts\Context;

use Behat\Behat\Context\BehatContext;
use Behat\Gherkin\Node\TableNode;
use Behat\Symfony2Extension\Context\KernelDictionary;

use Knp\FriendlyContexts\Dictionary\Contextable;
use Knp\FriendlyContexts\Dictionary\Symfony;
use Knp\FriendlyContexts\Doctrine\EntityResolver;
use Knp\FriendlyContexts\Reflection\ObjectReflector;
use Knp\FriendlyContexts\Doctrine\RecordCollectionBag;

class EntityContext extends BehatContext
{
    use Contextable,
        Symfony,
        KernelDictionary;

    protected $resolver;
    protected $collections;

    public function __construct(array $options = [])
    {
        $this->options = array_merge(
            [
                'Entities' => [''],
            ],
            $options
        );

        $this->collections = new RecordCollectionBag(new ObjectReflector());
    }

    /**
     * @Given /^the following (.*)$/
     */
    public function theFollowing($name, TableNode $table)
    {
        if (null === $this->resolver) {
            $this->resolver = new EntityResolver($this->getEntityManager());
        }

        $entityName = $this->resolveEntity($name)->getName();
        $collection = $this->collections->get($entityName);

        $rows = $table->getRows();
        $headers = array_shift($rows);

        foreach ($rows as $row) {
            $values = array_combine($headers, $row);
            $entity = new $entityName;
            $collection->attach($entity, $values);
        }
        var_dump($collection);

        die(var_dump('OKAY'));
    }

    protected function resolveEntity($name)
    {
        $entities = $this->resolver->resolve($name, $this->options['Entities']);
        switch (true) {
            case 1 < count($entities):
                throw new \Exception(
                    sprintf(
                        'Fail to find a unique model from the name "%s", "%s" found',
                        $name,
                        implode(
                            '" and "',
                            array_map(
                                function ($rfl) {
                                    return $rfl->getName();
                                },
                                $entities
                            )
                        )
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
}
