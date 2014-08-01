<?php

namespace Knp\FriendlyExtension\Gherkin\Node;

use Behat\Gherkin\Node\FeatureNode;
use Behat\Gherkin\Node\ScenarioNode;
use Behat\Gherkin\Node\TaggedNodeInterface;

class Explorer
{
    private $feature;
    private $scenario;

    public function __construct(FeatureNode $feature, ScenarioNode $scenario = null)
    {
        $this->feature  = $feature;
        $this->scenario = $scenario;
    }

    public function getTags($values = false)
    {
        $results = [];
        $this->extract($this->feature, $results);
        if (null !== $this->scenario) {
            $this->extract($this->scenario, $results);
        }

        return $values ? $results : array_keys($results);
    }

    public function hasTag($tag)
    {
        return in_array($tag, $this->getTags());
    }

    public function getArguments($tag)
    {
        if (false === $this->hasTag($tag)) {

            return;
        }

        $tags = $this->getTags(true);

        return $tags[$tag];
    }

    private function extract(TaggedNodeInterface $element, array &$results)
    {
        foreach ($element->getTags() as $tag)
        {
            $negative  = (0 === strpos($tag, '~'));
            $tag = $negative ? substr($tag, 1) : $tag;
            $matches = [];
            if (0 !== preg_match('/^(.+)\((.*)\)$/', $tag, $matches)) {
                list($str, $tag, $arguments) = $matches;
                $arguments = explode(',', $arguments);
                $arguments = array_map('trim', $arguments);
                $arguments = array_filter($arguments, function ($e) { return false === empty($e); });
                if ($negative) {
                    foreach ($arguments as $argument) {
                        if (
                            array_key_exists($tag, $results)
                            && false !== $index = array_search($argument, $results[$tag])
                        ) {
                            unset($results[$tag][$index]);
                        }
                    }
                } else {
                    foreach ($arguments as $argument) {
                        $results[$tag][] = $argument;
                    }
                }
                sort($results[$tag]);
            } else {
                if ($negative) {
                    if (array_key_exists($tag, $results)) {
                        unset($results[$tag]);
                    }
                } else {
                    $results[$tag] = [];
                }
            }
        }

        ksort($results);
    }
}
