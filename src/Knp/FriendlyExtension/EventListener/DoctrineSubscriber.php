<?php

namespace Knp\FriendlyExtension\EventListener;

use Knp\FriendlyExtension\Context\Helper\DoctrineHelper;
use Knp\FriendlyExtension\Gherkin\TagLoader;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class DoctrineSubscriber implements EventSubscriberInterface
{
    const RESET_TAG = 'reset-schema';

    private $helper;
    private $resetSchema;
    private $loader;

    public function __construct($resetSchema, DoctrineHelper $helper, TagLoader $loader)
    {
        $this->resetSchema = $resetSchema;
        $this->helper      = $helper;
        $this->loader      = $loader;
    }

    public static function getSubscribedEvents()
    {
        return [
            'tester.scenario_tested.before' => 'beforeScenario',
        ];
    }

    public function beforeScenario()
    {
        $tag = $this->loader->getTag(self::RESET_TAG);

        if (false === $this->resetSchema || (null !== $tag && $tag->enabled())) {

            return;
        }

        $this->helper->resetSchema();
    }
}
