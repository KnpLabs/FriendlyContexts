<?php

namespace spec\Knp\FriendlyContexts\Reader;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Behat\Gherkin\Gherkin;
use Behat\Testwork\Suite\SuiteRepository;
use Behat\Testwork\Specification\Locator\SpecificationLocator;
use Knp\FriendlyContexts\Call\CallCenter;
use Knp\FriendlyContexts\Definition\DefinitionFinder;

class SmartReaderSpec extends ObjectBehavior
{
    function let(Gherkin $gherkin, SuiteRepository $registry, SpecificationLocator $locator, DefinitionFinder $definitionFinder, CallCenter $callCenter)
    {
        $this->beConstructedWith($gherkin, $registry, $locator, $definitionFinder, $callCenter, 'smartStep');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Reader\SmartReader');
    }
}
