<?php

namespace Knp\FriendlyContexts\Dictionary;

use Behat\Behat\Hook\Scope\ScenarioScope;

trait Taggable
{
    protected $tags = [];
    protected $tagLoaded = false;

    /**
     * @BeforeScenario
     */
    public function storeTags($event)
    {
        if (false === $this->tagLoaded) {
            if ($event instanceof ScenarioScope) {
                if (null !== $feature = $event->getFeature()) {
                    $this->tags = array_merge($this->tags, $feature->getTags());
                }
                if (null !== $scenario = $event->getScenario()) {
                    $this->tags = array_merge($this->tags, $scenario->getTags());
                }
            }
            $this->tagLoaded = true;
        }
    }

    protected function hasTag($name)
    {
        return in_array($name, $this->tags);
    }

    protected function hasTags(array $names)
    {
        foreach ($names as $name) {
            if (!(0 === strpos($name, '~')) !== $this->hasTag(str_replace('~', '', $name))) {
                return false;
            }
        }

        return true;
    }

    protected function getTagContent($name)
    {
        $content = [];

        foreach ($this->tags as $tag) {
            $matches = [];
            if (preg_match(sprintf('/^%s\((.*)\)$/', $name), $tag, $matches)) {
                $content[] = end($matches);
            }
        }

        return $content;
    }

    protected function getTags()
    {
        return $this->tags;
    }
}
