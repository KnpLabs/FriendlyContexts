<?php

namespace Knp\FriendlyContexts\Dictionary;

use Behat\Behat\Event\ScenarioEvent;
use Behat\Behat\Event\OutlineExampleEvent;

trait Taggable
{
    protected $tags;

    /**
     * @BeforeScenario
     */
    public function storeTags($event)
    {
        if ($event instanceof ScenarioEvent) {
            $this->tags = $event->getScenario()->getTags();
        } elseif ($event instanceof OutlineExampleEvent) {
            $this->tags = $event->getOutline()->getTags();
        }

        var_dump($this->tags);
    }

    protected function hasTag($name)
    {
        return in_array($name, $this->tags);
    }

    protected function hasTags(array $names)
    {
        foreach ($names as $name) {
            if (!(0 === strpos($name, '~')) === $this->hasTag(str_replace('~', '', $name))) {
                return false;
            }
        }

        return true;
    }
}
