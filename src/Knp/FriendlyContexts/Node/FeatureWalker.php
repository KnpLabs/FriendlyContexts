<?php

namespace Knp\FriendlyContexts\Node;

use Behat\Gherkin\Gherkin;

class FeatureWalker
{
    protected $gherkin;
    protected $paths;
    protected $loaded;
    protected $features = [];

    public function __construct(Gherkin $gherkin, $paths)
    {
        $this->gherkin = $gherkin;
        $this->paths   = $paths;
        $this->loaded  = false;
    }

    public function getScenarioByName($name)
    {
        foreach ($this->getScenarios() as $scenario) {
            if ($name === $scenario->getTitle()) {
                return $scenario;
            }
        }
    }

    public function getScenarios()
    {
        $scenarios = [];
        foreach ($this->getFeatures() as $feature) {
            $scenarios = array_merge($scenarios, $feature->getScenarios());
        }

        return $scenarios;
    }

    public function getFeatures()
    {
        if (!$this->loaded) {
            $this->features = $this->gherkin->load($this->paths);
            $this->loaded = true;
        }

        return $this->features;
    }
}
