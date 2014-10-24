<?php

namespace Knp\FriendlyExtension\Mink;

use Behat\Gherkin\Node\ScenarioInterface;
use Behat\Gherkin\Node\StepNode;
use Knp\FriendlyExtension\Mink\Screenshot;
use Knp\FriendlyExtension\Mink\ScreenshotMailer;
use Knp\FriendlyExtension\Mink\Screenshot\Loader;

class ScreenshotHandler
{
    private $mailer;
    private $loaders     = [];
    private $recipients  = [];
    private $screenshots = [];

    public function __construct(ScreenshotMailer $mailer, array $recipients)
    {
        $this->mailer     = $mailer;
        $this->recipients = $recipients;
    }

    public function addLoader(Loader $loader)
    {
        $this->loaders[] = $loader;
    }

    public function take(ScenarioInterface $scenario = null)
    {
        if (0 === count($this->recipients)) {

            return;
        }

        $loader = null;

        foreach ($this->loaders as $current) {
            if ($current->supports()) {
                $loader = $current;
            }
        }

        if (null === $loader) {

            return;
        }

        return $this->screenshots[] = new Screenshot($scenario, $loader->take(), $loader->getExtension(), $loader->getMimeType());
    }

    public function notify()
    {
        if (0 === count($this->screenshots) || 0 === count($this->recipients)) {

            return;
        }

        $this->mailer->send($this->screenshots, $this->recipients);
    }
}
