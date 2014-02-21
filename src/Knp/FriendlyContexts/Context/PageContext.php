<?php

namespace Knp\FriendlyContexts\Context;

use Doctrine\Common\Inflector\Inflector;
use Behat\Gherkin\Node\TableNode;

class PageContext extends RawPageContext
{
    /**
     * @Given /^I am on the (.*) page$/
     * @When /^I go to the (.*) page$/
     */
    public function iAmOnThePage($page)
    {
        $page = $this->getPage($page);
        $path = $this->locatePath($page->getPath());

        $this->getSession()->visit($path);
    }

    /**
     * @Then /^I should be on the (.*) page$/
     */
    public function iShouldBeOnThePage($page)
    {
        $page = $this->getPage($page);
        $path = $this->locatePath($page->getPath());

        $this->assertSession()->addressEquals($page);
    }

    /**
     * @Given /^I am on the (.*) page with:?$/
     * @When /^I go to the (.*) page with:?$/
     */
    public function iAmOnThePageWith($page, TableNode $table)
    {
        list($parameters, $entities) = $this->extractTable($table);

        $page = $this->getPage($page);
        $path = $this->locatePath($this->resolvePagePath($page, $parameters, $entities));

        die(var_dump($path));

        $this->getSession()->visit($page);
    }

    /**
     * @Then /^I should be on the (.*) page with:?$/
     */
    public function iShouldBeOnThePageWith($page, TableNode $table)
    {
        list($parameters, $entities) = $this->extractTable($table);

        $page = $this->getPage($page);
        $path = $this->locatePath($this->resolvePagePath($page, $parameters, $entities));

        $this->assertSession()->addressEquals($page);
    }

    protected function extractTable(TableNode $table)
    {
        $parameters = $table->getRowsHash();

        $entities = [];

        foreach ($parameters as $name => $value) {
            $matches = array();
            if (preg_match('/^the (.+) "([^"]+)"$/', $value, $matches)) {
                $entity = $this->getEntityFromRecordBag($matches[1], $matches[2]);

                $entities[$name] = $entity;
                unset($parameters[$name]);
            }
        }

        return array($parameters, $entities);
    }
}
