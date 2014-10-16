<?php

namespace Knp\FriendlyExtension\Context;

use Behat\MinkExtension\Context\MinkContext as BaseContext;
use Knp\FriendlyExtension\Context\ContextInterface;
use Knp\FriendlyExtension\Context\Helper\Registry;

class MinkContext extends BaseContext implements ContextInterface
{
    private $registry;

    public function setHelperRegistry(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @When I follow the ":name" ":element"
     * @When I press the ":name" ":element"
     * @When I follow the first ":name" ":element"
     * @When I press the first ":name" ":element"
     * @When /^I (follow|press) the (?P<nbr>\d*)(st|nd|rd|th) "(?P<name>[^"]*)" (?P<element>[^"]*)$/
     **/
    public function clickElement($name, $element, $nbr = 1, $filterCallback = null)
    {
        $this->get('mink')->elementAction($name, $element, $nbr, function ($e) { $e->click(); }, $filterCallback);
    }

    /**
     * @When /^I (?P<state>check|uncheck) the "(?P<name>[^"]*)" checkbox$/
     * @When /^I (?P<state>check|uncheck) the first "(?P<name>[^"]*)" checkbox$/
     * @When /^I (?P<state>check|uncheck) the (?P<nbr>\d*)(st|nd|rd|th) "(?P<name>[^"]*)" checkbox$/
     **/
    public function checkCheckbox($state, $name, $nbr = 1)
    {
        $this->get('mink')->elementAction(
            $name,
            'field',
            $nbr,
            function ($e) use ($state) { if ('check' === $state) { $e->check(); } else { $e->uncheck(); } },
            function ($e) { return 'checkbox' === $e->getAttribute('type'); }
        );
    }

    /**
     * @When /^I check the ":name" radio
     * @When /^I check the first ":name" radio
     * @When /^I check the (?P<nbr>\d*)(st|nd|rd|th) "(?P<name>[^"]*)" radio$/
     **/
    public function checkRadio($name, $nbr = 1)
    {
        $this->get('mink')->elementAction(
            $name,
            'field',
            $nbr,
            function ($e) { $this->getSession()->getDriver()->click($e->getXPath()); },
            function ($e) { return 'radio' === $e->getAttribute('type'); }
        );
    }


    /**
     * @Then /^I should(?P<should>| not) see (?P<nbr>\d*) "(?P<name>[^"]*)" (?P<element>link|button|radio|checkbox)$/
     **/
    public function nbrElement($should, $nbr, $name, $element)
    {
        $type = in_array($element, [ 'checkbox', 'radio' ]) ? 'field' : $element;
        $filterCallback = null;

        if ('field' === $type) {
            $filterCallback = function ($e) use ($element) { return $element === $e->getAttribute('type'); };
        }

        $elements = $this->get('mink')->searchElement($name, $type, $filterCallback);

        $message = sprintf('%s %s found', $nbr, $element);

        if (' not' === $should) {
            $this->get('asserter')->assertEquals($nbr, count($elements), $message);
        } else {
            $this->get('asserter')->assertNotEquals($nbr, count($elements), $message);
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

        $elements = $this->get('mink')->searchElement($name, $type, $filterCallback);

        $message = sprintf('%s %s%s found', $name, $element, ' not' === $should ? '' : ' not');

        if (' not' === $should) {
            $this->get('asserter')->assert(0 == count($elements), $message);
        } else {
            $this->get('asserter')->assert(0 < count($elements), $message);
        }
    }

    /**
     * @When I follow the last ":name" ":element"
     * @When I press the last ":name" ":element"
     **/
    public function clicklastElement($name, $element)
    {
        $this->clickElement($name, $element, -1);
    }

    /**
     * @When I follow the link containing ":link"
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
     * @When I fill in the first ":field" field with ":value"
     * @When /^(?:|I )fill in the (?P<nbr>\d*)(st|nd|rd|th) "(?P<field>(?:[^"]|\\")*)" field with "(?P<value>(?:[^"]|\\")*)"$/
     **/
    public function fillTheNthField($field, $value, $nbr = 1)
    {
        $field = $this->get('mink')->fixStepArgument($field);
        $value = $this->get('mink')->fixStepArgument($field);

        $this->get('mink')->elementAction(
            $field,
            'field',
            $nbr,
            function ($e) use ($value) { $e->setValue($value); },
            function ($e) { return 'text' === $e->getAttribute('type'); }
        );
    }

    protected function get($name)
    {
        return $this->registry->get($name);
    }
}
