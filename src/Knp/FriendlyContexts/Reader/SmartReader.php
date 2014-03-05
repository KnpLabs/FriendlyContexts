<?php

namespace Knp\FriendlyContexts\Reader;

use Behat\Testwork\Environment\Reader\EnvironmentReader;
use Behat\Testwork\Environment\Environment;
use Behat\Testwork\Specification\Locator\SpecificationLocator;
use Behat\Testwork\Suite\SuiteRegistry;
use Behat\Gherkin\Gherkin;
use Behat\Gherkin\Filter\TagFilter;
use Behat\Behat\Tester\StepTester;
use Behat\Behat\Tester\Result\StepContainerTestResult;
use Behat\Testwork\Tester\Result\TestResults;
use Behat\Behat\Definition\Call;

class SmartReader implements EnvironmentReader
{
    public function __construct(Gherkin $gherkin, SuiteRegistry $registry, SpecificationLocator $locator, StepTester $stepTester, $smartTag)
    {
        $this->gherkin    = $gherkin;
        $this->registry   = $registry;
        $this->locator    = $locator;
        $this->stepTester = $stepTester;
        $this->smartTag   = $smartTag;
    }

    public function supportsEnvironment(Environment $environment)
    {
        return true;
    }

    public function readEnvironmentCallees(Environment $environment)
    {
        $callees = [];

        foreach ($this->extractScenarios() as $data) {
            list($feature, $scenarios) = $data;
            foreach ($scenarios as $scenario) {
                $callable = function () use ($environment, $feature, $scenario) {
                    // @TODO
                };

                $callees = array_merge($callees, $this->buildCallee($scenario, $callable));
            }
        }

        return $callees;
    }

    public function extractScenarios()
    {
        $scenarios = [];

        foreach ($this->registry->getSuites() as $suite) {
            foreach ($this->locator->locateSpecifications($suite, '') as $feature) {
                $collection = array_filter($feature->getScenarios(), function ($e) { return $e->hasTag($this->smartTag); });
                $scenarios[] = [ $feature, $collection ];
            }
        }

        return $scenarios;
    }

    protected function buildCallee($scenario, $callable)
    {
        return [
            new Call\Given(sprintf('/^%s$/', $scenario->getTitle()), $callable),
        ];
    }
}
