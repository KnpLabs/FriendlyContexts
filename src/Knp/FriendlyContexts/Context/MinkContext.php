<?php

namespace Knp\FriendlyContexts\Context;

use Behat\MinkExtension\Context\MinkContext as BaseMinkContext;

class MinkContext extends BaseMinkContext
{
    /**
     * @When /^(?:|I )(follow|press) the first "(?P<name>[^"]*)" (?P<element>[^"]*)$/
     * @When /^(?:|I )(follow|press) the (?P<nbr>\d*)(st|nd|rd|th) "(?P<name>[^"]*)" (?P<element>[^"]*)$/
     **/
    public function clickElement($name, $element, $nbr = 1, $filterCallback = null)
    {
        $page  = $this->getSession()->getPage();
        $elements = $page->findAll('named', array(
            $element, $this->getSession()->getSelectorsHandler()->xpathLiteral($name)
        ));

        if (null !== $filterCallback && is_callable($filterCallback)) {
            $elements = array_filter($elements, $filterCallback);
            $elements = array_values($elements);
        }

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
     * @When /^(?:|I )(follow|press) the last "(?P<name>[^"]*)" (?P<element>[^"]*)$/
     **/
    public function clicklastElement($name, $element)
    {
        $this->clickElement($link, $element, -1);
    }

    /**
     * @When /^(?:|I )follow the link containing "(?P<link>(?:[^"]|\\")*)"$/
     */
    public function clickLinkContaining($link)
    {
        parent::clickLink($link);
    }

    public function clickLink($link)
    {
        $this->clickElement($link, 'link', 1, function ($e) use ($link) { return $link === $e->getText(); });
    }
}
