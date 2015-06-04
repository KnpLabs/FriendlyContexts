<?php

namespace Knp\FriendlyContexts\Reader;

use Behat\Behat\Definition\Call;
use Behat\Behat\Definition\Call\DefinitionCall;
use Behat\Behat\Definition\Exception\SearchException;
use Behat\Behat\Definition\SearchResult;
use Behat\Behat\Tester\Result\ExecutedStepResult;
use Behat\Behat\Tester\Result\SkippedStepResult;
use Behat\Behat\Tester\Result\UndefinedStepResult;
use Behat\Gherkin\Gherkin;
use Behat\Gherkin\Node\FeatureNode;
use Behat\Gherkin\Node\StepNode;
use Behat\Testwork\Environment\Environment;
use Behat\Testwork\Environment\Reader\EnvironmentReader;
use Behat\Testwork\Specification\Locator\SpecificationLocator;
use Behat\Testwork\Suite\SuiteRepository;
use Knp\FriendlyContexts\Call\CallCenter;
use Knp\FriendlyContexts\Definition\DefinitionFinder;

class SmartReader implements EnvironmentReader
{
    public function __construct(Gherkin $gherkin, SuiteRepository $registry, SpecificationLocator $locator, DefinitionFinder $definitionFinder, CallCenter $callCenter, $smartTag)
    {
        $this->gherkin          = $gherkin;
        $this->registry         = $registry;
        $this->locator          = $locator;
        $this->definitionFinder = $definitionFinder;
        $this->callCenter       = $callCenter;
        $this->smartTag         = $smartTag;
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
                    $steps = $scenario->getSteps();
                    foreach ($steps as $step) {
                        $result = $this->testStep($environment, $feature, $step);

                        if ($result instanceof SkippedStepResult) {
                            throw new \RuntimeException('Step has been skipped.');
                        } elseif (true === $result->hasException()) {
                            throw $result->getException();
                        }
                    }
                };

                $callees = array_merge($callees, $this->buildCallee($feature, $scenario, $callable));
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

    protected function buildCallee($feature, $scenario, $callable)
    {
        $description = sprintf('%s:%s', $feature->getFile(), $scenario->getLine());

        return [
            new Call\Given(sprintf('/^%s$/', $scenario->getTitle()), $callable, $description),
        ];
    }

    protected function testStep(Environment $environment, FeatureNode $feature, StepNode $step, $skip = false)
    {
        try {
            $search = $this->searchDefinition($environment, $feature, $step);
            $result = $this->testDefinition($environment, $feature, $step, $search, $skip);
        } catch (SearchException $exception) {
            $result = new UndefinedStepResult();
        }

        return $result;
    }

    private function searchDefinition(Environment $environment, FeatureNode $feature, StepNode $step)
    {
        return $this->definitionFinder->findDefinition($environment, $feature, $step);
    }

    private function testDefinition(Environment $environment, FeatureNode $feature, StepNode $step, SearchResult $search, $skip = false)
    {
        if ($skip || !$search->hasMatch()) {
            return new SkippedStepResult($search, null, null);
        }

        $call = $this->createDefinitionCall($environment, $feature, $search, $step);
        $result = $this->callCenter->makeCall($call);

        return new ExecutedStepResult($search, $result);
    }

    private function createDefinitionCall(Environment $environment, FeatureNode $feature, SearchResult $search, StepNode $step)
    {
        $definition = $search->getMatchedDefinition();
        $arguments = $search->getMatchedArguments();

        return new DefinitionCall($environment, $feature, $step, $definition, $arguments);
    }
}
