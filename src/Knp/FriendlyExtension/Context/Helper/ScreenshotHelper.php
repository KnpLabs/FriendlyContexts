<?php

namespace Knp\FriendlyExtension\Context\Helper;

use Knp\FriendlyExtension\Mink\ScreenshotHandler;
use Swift_Mailer;

class ScreenshotHelper extends AbstractHelper
{
    public function __construct(Swift_Mailer $mailer, array $recipeints, ScreenshotHandler $handler = null)
    {
        $this->mailer     = $mailer;
        $this->recipeints = $recipeints;
        $this->handler    = $handler;
    }

}
