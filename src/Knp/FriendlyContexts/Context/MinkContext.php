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
        $this->elementAction($name, $element, $nbr, function ($e) { $e->click(); }, $filterCallback);
    }

    /**
     * @When /^(?:|I )(?P<state>check|uncheck) the "(?P<name>[^"]*)" (?P<element>radio|checkbox)$/
     * @When /^(?:|I )(?P<state>check|uncheck) the first "(?P<name>[^"]*)" (?P<element>radio|checkbox)$/
     * @When /^(?:|I )(?P<state>check|uncheck) the (?P<nbr>\d*)(st|nd|rd|th) "(?P<name>[^"]*)" (?P<element>radio|checkbox)$/
     **/
    public function checkElement($state, $name, $element, $nbr = 1)
    {
        $this->elementAction(
            $name,
            'field',
            $nbr,
            function ($e) use ($state)   { if ('check' === $state) { $e->check(); } else { $e->uncheck(); } },
            function ($e) use ($element) { return $element === $e->getAttribute('type'); }
        );
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

    protected function elementAction($locator, $element, $nbr = 1, $actionCallback, $filterCallback = null)
    {
        $page  = $this->getSession()->getPage();
        $elements = $page->findAll('named', array(
            $element, $this->getSession()->getSelectorsHandler()->xpathLiteral($locator)
        ));

        if (null !== $filterCallback && is_callable($filterCallback)) {
            $elements = array_filter($elements, $filterCallback);
            $elements = array_values($elements);
        }

        $nbr = -1 === $nbr ? count($elements) : $nbr;

        if ($nbr > count($elements)) {
            throw new \Exception(sprintf(
                'Expected to find almost %s "%s" %s, %s found', $nbr, $locator, $element, count($elements)
            ));
        }

        $e = $elements[$nbr - 1];

        $actionCallback($e);
    }
}
