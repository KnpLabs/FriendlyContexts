<?php

namespace Knp\FriendlyExtension\Context;

use Behat\Gherkin\Node\TableNode;
use Knp\FriendlyExtension\Context\Context;

class PageContext extends Context
{
    /**
     * @Given I am on the :page page
     * @Given I am on the :page page with:
     * @When I go to the :page page
     * @When I go to the :page page with:
     */
    public function iAmOnThePageWith($page, TableNode $table = null)
    {
        $page = $this->get('page')->getPagePath($page, $table);
        $this
            ->get('mink')
            ->getSession()
            ->visit(
                $this->get('mink')->locatePath($page)
            )
        ;
    }

    /**
     * @Then /^I should be on the (.*) page$/
     * @Then /^I should be on the (.*) page with:?$/
     */
    public function iShouldBeOnThePageWith($page, TableNode $table = null)
    {
        $page = $this->get('page')->getPagePath($page, $table);
        try {
            $this->get('mink')->assertSession()->addressEquals($page);
        } catch (\Exception $e) {
            $this
                ->get('mink')
                ->assertSession()
                ->addressEquals(
                    sprintf('%s/', $page)
                )
            ;
        }
    }
}
