<?php

namespace Knp\FriendlyContexts\Context;

use Behat\MinkExtension\Context\MinkContext as BaseMinkContext;

class MinkContext extends BaseMinkContext
{
    /**
     * @When /^(?:|I )follow the first "(?P<link>[^"]*)" link$/
     * @When /^(?:|I )follow the (?P<nbr>\d*)st "(?P<link>[^"]*)" link$/
     * @When /^(?:|I )follow the (?P<nbr>\d*)nd "(?P<link>[^"]*)" link$/
     * @When /^(?:|I )follow the (?P<nbr>\d*)rd "(?P<link>[^"]*)" link$/
     * @When /^(?:|I )follow the (?P<nbr>\d*)th "(?P<link>[^"]*)" link$/
     **/
    public function clickLink($link, $nbr = 1)
    {
        $page  = $this->getSession()->getPage();
        $links = $page->findAll('css', 'a');

        $links = array_values(
            array_filter($links, function ($e) use ($link) {
                return $link === $e->getText();
            } )
        );

        $nbr = -1 === $nbr ? count($links) : $nbr;

        if ($nbr > count($links)) {
            throw new \Exception(sprintf(
                'Expected to find almost %s "%s" link, %s found', $nbr, $link, count($links)
            ));
        }

        $link = $links[$nbr - 1];
        $link->click();
    }

    /**
     * @When /^(?:|I )follow the last "(?P<link>[^"]*)" link$/
     **/
    public function clicklastLink($link)
    {
        $this->clickLink($link, -1);
    }

    /**
     * @When /^(?:|I )follow the link containing "(?P<link>(?:[^"]|\\")*)"$/
     */
    public function clickLinkContaining($link)
    {
        parent::clickLink($link);
    }
}
