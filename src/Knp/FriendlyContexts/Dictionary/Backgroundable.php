<?php

namespace Knp\FriendlyContexts\Dictionary;

use Behat\Behat\Tester\Event\StepTested;

trait Backgroundable
{
    private $HOOK_BEFORE_BACKGROUND = 'BeforeBackground';
    private $HOOK_AFTER_BACKGROUND  = 'AfterBackground';

    private $inBackground = false;

    /**
     * @BeforeStep
     **/
    public function BackgroundDispatcher(StepTested $event)
    {
        $feature = $event->getFeature();
        $background = $feature->getBackground() ?: array();
        $underBackground = in_array($event->getStep(), $background->getSTeps());

        if ($underBackground !== $this->inBackground) {
            if (true === $underBackground) {
                $this->displachEvent($this->HOOK_BEFORE_BACKGROUND, $event);
            } else {
                $this->displachEvent($this->HOOK_AFTER_BACKGROUND, $event);
            }

            $this->inBackground = $underBackground;
        }
    }

    protected function displachEvent($name, $event)
    {
        $methods = $this->getHookMethodsByName($name);

        foreach ($methods as $method) {
            $this->$method($event);
        }
    }

    protected function getHookMethodsByName($name)
    {
        $methods = [];
        $rfl = new \ReflectionClass($this);

        foreach ($rfl->getMethods() as $method) {
            $comments = explode("\n", $method->getDocComment());

            $tags = [];
            foreach ($comments as $comment) {
                if (preg_match('/@(\w*)/', $comment, $tags)) {
                    if (in_array($name, $tags)) {
                        $methods[] = $method->name;
                    }
                }
            }
        }

        return array_unique($methods);
    }
}
