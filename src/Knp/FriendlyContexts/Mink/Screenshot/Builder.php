<?php

namespace Knp\FriendlyContexts\Mink\Screenshot;

use Behat\Mink\Session;

interface Builder
{
    public function buildScreenshot(Session $session);
    public function getFormat();
}
