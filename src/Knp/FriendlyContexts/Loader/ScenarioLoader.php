<?php

namespace Knp\FriendlyContexts\Loader;

use Behat\Behat\Definition\Annotation\Given;
use Behat\Behat\Context\ContextInterface;
use Behat\Behat\Context\Loader\LoaderInterface;
use Behat\Behat\Definition\DefinitionDispatcher;
use Knp\FriendlyContexts\Node\FeatureWalker;

class ScenarioLoader implements LoaderInterface
{
    public function __construct(DefinitionDispatcher $definitionDispatcher, FeatureWalker $featureWalker)
    {
        $this->definitionDispatcher = $definitionDispatcher;
        $this->featureWalker        = $featureWalker;
    }

    public function supports(ContextInterface $context)
    {
        return $context instanceof \Knp\FriendlyContexts\Context\SmartContext;
    }

    public function load(ContextInterface $context)
    {
        $scenarios = $this->featureWalker->getScenarios();

        foreach ($scenarios as $scenario) {
            $definition = new Given(
                function() use ($context, $scenario) {
                    return $context->executeScenario($scenario);
                },
                $scenario->getTitle()
            );

            $this->definitionDispatcher->addDefinition($definition);
        }
    }
}
