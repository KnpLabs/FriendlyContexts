<?php

namespace Knp\FriendlyContexts\Context;

use Behat\MinkExtension\Context\MinkContext as BaseMinkContext;

class MinkContext extends BaseMinkContext
{
    /**
     * @When /^(?:|I )follow the first "(?P<name>[^"]*)" (?P<element>[^"]*)$/
     * @When /^(?:|I )(follow|press) the (?P<nbr>\d*)(st|nd|rd|th) "(?P<name>[^"]*)" (?P<element>[^"]*)$/
     **/
    public function clickElement($name, $element, $nbr = 1)
    {
        $page  = $this->getSession()->getPage();
        $elements = $page->findAll('named', array(
            $element, $this->getSession()->getSelectorsHandler()->xpathLiteral($name)
        ));

        $nbr = -1 === $nbr ? count($elements) : $nbr;

        if ($nbr > count($elements)) {
            throw new \Exception(sprintf(
                'Expected to find almost %s "%s" %s, %s found', $nbr, $name, $element, count($elements)
            ));
        }

        $e = $elements[$nbr - 1];
        $e->click();
    }

    /**
     * @When /^(?:|I )follow the last "(?P<name>[^"]*)" (?P<element>[^"]*)$/
     **/
    public function clicklastLink($name, $element)
    {
        $this->clickLink($link, $element, -1);
    }

    /**
     * @When /^(?:|I )follow the link containing "(?P<link>(?:[^"]|\\")*)"$/
     */
    public function clickLinkContaining($link)
    {
        parent::clickLink($link);
    }
}
