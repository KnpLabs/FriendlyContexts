<?php

namespace Knp\FriendlyExtension\EventListener;

use Behat\Behat\EventDispatcher\Event\AfterScenarioTested;
use Behat\Testwork\EventDispatcher\Event\AfterSuiteTested;
use Behat\Testwork\Tester\Result\TestResult;
use Knp\FriendlyExtension\Mink\ScreenshotHandler;
use Knp\FriendlyExtension\Mink\ScreenshotMailer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ScreenshotSubscriber implements EventSubscriberInterface
{
    public function __construct(ScreenshotHandler $handler)
    {
        $this->handler    = $handler;
    }

    public static function getSubscribedEvents()
    {
        return [
            'tester.scenario_tested.after' => 'afterScenario',
            'tester.example_tested.after' => 'afterScenario',
            'tester.suite_tested.after' => 'afterSuite',
        ];
    }

    public function afterScenario(AfterScenarioTested $event)
    {
        $scenario = $event->getScenario();
        $result   = $event->getTestResult();

        switch ($result->getResultCode()) {
            case TestResult::FAILED:
                $this->handler->take($scenario);
                return;
        }
    }

    public function afterSuite(AfterSuiteTested $event)
    {
        $this->handler->notify();
    }
}
