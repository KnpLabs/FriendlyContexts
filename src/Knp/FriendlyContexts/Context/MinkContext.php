<?php

namespace Knp\FriendlyContexts\Context;

use Behat\MinkExtension\Context\MinkContext as BaseMinkContext;
use Behat\Mink\Element\TraversableElement;
use Behat\Mink\Exception\ElementNotFoundException;
use Knp\FriendlyContexts\Utils\Asserter;
use Knp\FriendlyContexts\Utils\TextFormater;

class MinkContext extends BaseMinkContext
{
    /**
     * @When /^(?:|I )(follow|press) the "(?P<name>[^"]*)" (?P<element>[^"]*)$/
     * @When /^(?:|I )(follow|press) the first "(?P<name>[^"]*)" (?P<element>[^"]*)$/
     * @When /^(?:|I )(follow|press) the (?P<nbr>\d*)(st|nd|rd|th) "(?P<name>[^"]*)" (?P<element>[^"]*)$/
     **/
    public function clickElement($name, $element, $nbr = 1, $filterCallback = null)
    {
        $this->elementAction($name, $element, $nbr, function ($e) { $e->click(); }, $filterCallback);
    }

    /**
     * @When /^(?:|I )(?P<state>check|uncheck) the "(?P<name>[^"]*)" checkbox$/
     * @When /^(?:|I )(?P<state>check|uncheck) the first "(?P<name>[^"]*)" checkbox$/
     * @When /^(?:|I )(?P<state>check|uncheck) the (?P<nbr>\d*)(st|nd|rd|th) "(?P<name>[^"]*)" checkbox$/
     **/
    public function checkCheckbox($state, $name, $nbr = 1)
    {
        $this->elementAction(
            $name,
            'field',
            $nbr,
            function ($e) use ($state) { if ('check' === $state) { $e->check(); } else { $e->uncheck(); } },
            function ($e) { return 'checkbox' === $e->getAttribute('type'); }
        );
    }

    /**
     * @When /^(?:|I )check the "(?P<name>[^"]*)" radio$/
     * @When /^(?:|I )check the first "(?P<name>[^"]*)" radio$/
     * @When /^(?:|I )check the (?P<nbr>\d*)(st|nd|rd|th) "(?P<name>[^"]*)" radio$/
     **/
    public function checkRadio($name, $nbr = 1)
    {
        $this->elementAction(
            $name,
            'field',
            $nbr,
            function ($e) { $this->getSession()->getDriver()->click($e->getXPath()); },
            function ($e) { return 'radio' === $e->getAttribute('type'); }
        );
    }


    /**
     * @Then /^(?:|I )should(?P<should>| not) see (?P<nbr>\d*) "(?P<name>[^"]*)" (?P<element>link|button|radio|checkbox)$/
     **/
    public function nbrElement($should, $nbr, $name, $element)
    {
        $type = in_array($element, [ 'checkbox', 'radio' ]) ? 'field' : $element;
        $filterCallback = null;

        if ('field' === $type) {
            $filterCallback = function ($e) use ($element) { return $element === $e->getAttribute('type'); };
        }

        $elements = $this->searchElement($name, $type, $filterCallback);

        $message = sprintf('%s %s found', $nbr, $element);

        if (' not' === $should) {
            $this->getAsserter()->assertEquals($nbr, count($elements), $message);
        } else {
            $this->getAsserter()->assertNotEquals($nbr, count($elements), $message);
        }
    }

    /**
     * @Then /^(?:|I )should(?P<should>| not) see a "(?P<name>[^"]*)" (?P<element>link|button|radio|checkbox)$/
     **/
    public function seeElement($should, $name, $element)
    {
        $type = in_array($element, [ 'checkbox', 'radio' ]) ? 'field' : $element;
        $filterCallback = null;

        if ('field' === $type) {
            $filterCallback = function ($e) use ($element) { return $element === $e->getAttribute('type'); };
        }

        $elements = $this->searchElement($name, $type, $filterCallback);

        $message = sprintf('%s %s%s found', $name, $element, ' not' === $should ? '' : ' not');

        if (' not' === $should) {
            $this->getAsserter()->assert(0 == count($elements), $message);
        } else {
            $this->getAsserter()->assert(0 < count($elements), $message);
        }
    }

    /**
     * @When /^(?:|I )(follow|press) the last "(?P<name>[^"]*)" (?P<element>[^"]*)$/
     **/
    public function clicklastElement($name, $element)
    {
        $this->clickElement($name, $element, -1);
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

    /**
     * @When /^(?:|I )fill in the first "(?P<field>(?:[^"]|\\")*)" field with "(?P<value>(?:[^"]|\\")*)"$/
     * @When /^(?:|I )fill in the (?P<nbr>\d*)(st|nd|rd|th) "(?P<field>(?:[^"]|\\")*)" field with "(?P<value>(?:[^"]|\\")*)"$/
     **/
    public function fillTheNthField($field, $value, $nbr = 1)
    {
        $field = $this->fixStepArgument($field);
        $value = $this->fixStepArgument($value);

        $this->elementAction(
            $field,
            'field',
            $nbr,
            function ($e) use ($value) { $e->setValue($value); },
            function ($e) {
                return in_array($e->getAttribute('type'), array(
                    'text', 'password', 'color', 'date', 'datetime',
                    'datetime-local', 'email', 'month', 'number', 'range',
                    'search', 'tel', 'time', 'url', 'week',
                ));
            }
        );
    }

    protected function searchElement($locator, $element, $filterCallback = null, TraversableElement $parent = null)
    {
        $parent  = $parent ?: $this->getSession()->getPage();
        $locator = $this->fixStepArgument($locator);

        $elements = $parent->findAll('named', array(
            $element, $locator
        ));

        if (null !== $filterCallback && is_callable($filterCallback)) {
            $elements = array_values(array_filter($elements, $filterCallback));
        }

        return $elements;
    }

    protected function elementAction($locator, $element, $nbr = 1, $actionCallback, $filterCallback = null)
    {
        $elements = $this->searchElement($locator, $element, $filterCallback);

        $nbr = is_numeric($nbr) ? intval($nbr) : $nbr;
        $nbr = is_string($nbr) ? 1 : (-1 === $nbr ? count($elements) : $nbr);

        if ($nbr > count($elements)) {
            throw new ElementNotFoundException($this->getSession(), $element, null, $locator);
        }

        $e = $elements[$nbr - 1];

        $actionCallback($e);
    }

    protected function getAsserter()
    {
        return new Asserter(new TextFormater);
    }
}
