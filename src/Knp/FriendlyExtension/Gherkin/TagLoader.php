<?php

namespace Knp\FriendlyExtension\Gherkin;

use Behat\Behat\EventDispatcher\Event\ScenarioLikeTested;
use Knp\FriendlyExtension\Gherkin\TagFactory;

class TagLoader
{
    private $factory;
    private $feature;
    private $scenario;
    private $tags = [];

    public function __construct(TagFactory $factory)
    {
        $this->factory = $factory;
    }

    public function beforeScenario(ScenarioLikeTested $event)
    {
        $this->feature  = $event->getFeature();
        $this->scenario = $event->getScenario();

        foreach ($this->extractTags() as $tag) {
            $tagActive = '~' !== substr($tag, 0, 1);
            $tag       = $tagActive ? $tag : substr($tag, 1);
            $arguments = [];
            if (0 !== preg_match('/^(.+)\((.*)\)$/', $tag, $matches)) {
                list($str, $tag, $arguments) = $matches;
                $arguments = explode(',', $arguments);
                $arguments = array_map('trim', $arguments);
                $arguments = array_filter($arguments, function ($e) { return !empty($e); });
            }

            $object = $this->getTag($tag) ?: $this->factory->create($tag);
            if ($tagActive) {
                $object->enable();
            } else {
                $object->disable();
            }

            foreach ($arguments as $argument) {
                $argumentActive = '~' !== substr($argument, 0, 1);
                $argument       = $argumentActive ? $argument : substr($argument, 1);
                $object->addArgument($argument, $argumentActive);
            }

            $this->tags[$tag] = $object;
        }
    }

    public function getTag($name)
    {
        if (false === array_key_exists($name, $this->tags)) {

            return;
        }

        return $this->tags[$name];
    }

    private function extractTags()
    {
        if (null === $this->feature || null === $this->scenario) {

            return [];
        }

        return array_merge(
            $this->feature->getTags(),
            $this->scenario->getTags()
        );
    }
}
