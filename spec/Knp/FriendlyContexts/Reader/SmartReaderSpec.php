<?php

namespace spec\Knp\FriendlyContexts\Reader;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Behat\Gherkin\Gherkin;
use Behat\Testwork\Suite\SuiteRegistry;
use Behat\Testwork\Specification\Locator\SpecificationLocator;
use Behat\Behat\Definition\DefinitionFinder;
use Behat\Testwork\Call\CallCenter;

class SmartReaderSpec extends ObjectBehavior
{
    function let(Gherkin $gherkin, SuiteRegistry $registry, SpecificationLocator $locator, DefinitionFinder $definitionFinder, CallCenter $callCenter)
    {
        $this->beConstructedWith($gherkin, $registry, $locator, $definitionFinder, $callCenter, 'smartStep');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Reader\SmartReader');
    }
}
