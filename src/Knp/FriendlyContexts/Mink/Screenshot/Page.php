<?php

namespace Knp\FriendlyContexts\Mink\Screenshot;

use Behat\Mink\Session;
use Behat\Mink\Exception\UnsupportedDriverActionException;

class Page implements Builder
{
    public function buildScreenshot(Session $session)
    {
        return $session->getPage()->getContent();
    }

    public function getFormat()
    {
        return 'html';
    }
}
