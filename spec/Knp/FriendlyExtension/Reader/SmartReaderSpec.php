<?php

namespace spec\Knp\FriendlyExtension\Reader;

use Behat\Gherkin\Gherkin;
use Behat\Testwork\Specification\Locator\SpecificationLocator;
use Behat\Testwork\Suite\SuiteRepository;
use Knp\FriendlyExtension\Call\CallCenter;
use Knp\FriendlyExtension\Definition\DefinitionFinder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SmartReaderSpec extends ObjectBehavior
{
    function let(Gherkin $gherkin, SuiteRepository $registry, SpecificationLocator $locator, DefinitionFinder $definitionFinder, CallCenter $callCenter)
    {
        $this->beConstructedWith($gherkin, $registry, $locator, $definitionFinder, $callCenter, 'smartStep');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyExtension\Reader\SmartReader');
    }
}
