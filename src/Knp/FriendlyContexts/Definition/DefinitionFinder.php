<?php

namespace Knp\FriendlyContexts\Definition;

use Behat\Testwork\Environment\Environment;
use Behat\Gherkin\Node\FeatureNode;
use Behat\Gherkin\Node\StepNode;
use Behat\Behat\Definition\DefinitionFinder as BaseDefinitionFinder;

class DefinitionFinder
{
    public function __construct(BaseDefinitionFinder $definitionFinder)
    {
        $this->definitionFinder = $definitionFinder;
    }

    public function findDefinition(Environment $environment, FeatureNode $feature, StepNode $step)
    {
        return $this->definitionFinder->findDefinition($environment, $feature, $step);
    }
}
