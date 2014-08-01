<?php

namespace Knp\FriendlyExtension\EventListener;

use Behat\Behat\EventDispatcher\Event\BeforeScenarioTested;
use Knp\FriendlyExtension\Context\Helper\DoctrineHelper;
use Knp\FriendlyExtension\Gherkin\Node\Explorer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class DoctrineSubscriber implements EventSubscriberInterface
{
    const RESET_TAG = 'reset-schema';

    private $helper;
    private $resetSchema;

    public function __construct($resetSchema, DoctrineHelper $helper)
    {
        $this->resetSchema = $resetSchema;
        $this->helper      = $helper;
    }

    public static function getSubscribedEvents()
    {
        return [
            'tester.scenario_tested.before' => 'beforeScenario',
        ];
    }

    public function beforeScenario(BeforeScenarioTested $event)
    {
        $explorer = new Explorer($event->getFeature(), $event->getScenario());

        if ($this->resetSchema || $explorer->hasTag(self::RESET_TAG)) {

            $this->helper->resetSchema();
        }
    }
}
