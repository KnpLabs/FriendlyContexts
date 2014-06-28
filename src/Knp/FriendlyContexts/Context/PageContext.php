<?php

namespace Knp\FriendlyContexts\Context;

use Behat\Gherkin\Node\TableNode;

class PageContext extends RawPageContext
{
    /**
     * @Given /^I am on the (.*) page$/
     * @Given /^I am on the (.*) page with:?$/
     * @When /^I go to the (.*) page$/
     * @When /^I go to the (.*) page with:?$/
     */
    public function iAmOnThePageWith($page, TableNode $table = null)
    {
        $this->visitPage($page, $table);
    }

    /**
     * @Then /^I should be on the (.*) page$/
     * @Then /^I should be on the (.*) page with:?$/
     */
    public function iShouldBeOnThePageWith($page, TableNode $table = null)
    {
        $this->assertPage($page, $table);
    }
}
