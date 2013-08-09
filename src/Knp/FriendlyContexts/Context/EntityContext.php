<?php

namespace Knp\FriendlyContexts\Context;

use Behat\Behat\Context\BehatContext;
use Behat\Gherkin\Node\TableNode;
use Behat\Symfony2Extension\Context\KernelDictionary;

use Knp\FriendlyContexts\Dictionary\Contextable;
use Knp\FriendlyContexts\Dictionary\Symfony;
use Knp\FriendlyContexts\Doctrine\EntityResolver;

class EntityContext extends BehatContext
{
    use Contextable,
        Symfony,
        KernelDictionary;

    public function __construct(array $options = [])
    {
        $this->options = array_merge(
            [
                'Entities' => [],
            ],
            $options
        );

        $this->resolver = new EntityResolver($this->getEntityManager(), $this->options['Entities']);
    }

    /**
     * @Given /^the following (.*)$/
     */
    public function theFollowing($name, TableNode $table)
    {
        $rows = $table->getRows();
        $headers = array_shift($rows);

        $entity = $this->resolver->resolve($name);

        var_dump($rows, $headers);

        die(var_dump('OKAY'));
    }
}
