<?php

namespace Knp\FriendlyExtension\Mink;

use Behat\Gherkin\Node\ScenarioInterface;

class Screenshot
{
    public function __construct(ScenarioInterface $scenario, $content, $extension, $mimeType)
    {
        $this->scenario  = $scenario;
        $this->content   = $content;
        $this->extension = $extension;
        $this->mimeType  = $mimeType;
    }

    public function getScenario()
    {
        return $this->scenario;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getExtension()
    {
        return $this->extension;
    }

    public function getMimeType()
    {
        return $this->mimeType;
    }
}
