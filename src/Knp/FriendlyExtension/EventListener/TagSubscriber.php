<?php

namespace Knp\FriendlyExtension\EventListener;

use Behat\Behat\EventDispatcher\Event\BeforeScenarioTested;
use Knp\FriendlyExtension\Gherkin\TagLoader;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TagSubscriber implements EventSubscriberInterface
{
    public function __construct(TagLoader $loader)
    {
        $this->loader = $loader;
    }

    public static function getSubscribedEvents()
    {
        return [
            'tester.scenario_tested.before' => 'beforeScenario',
            'tester.example_tested.before' => 'beforeScenario',
        ];
    }

    public function beforeScenario(BeforeScenarioTested $event)
    {
        $this->loader->load($event->getFeature(), $event->getScenario());
    }
}
