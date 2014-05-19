<?php

namespace Knp\FriendlyContexts\Mink\Screenshot;

use Behat\Mink\Session;
use Behat\Mink\Exception\UnsupportedDriverActionException;

class Picture implements Builder
{
    public function buildScreenshot(Session $session)
    {
        try {
            return $session->getDriver()->getScreenshot();
        } catch (UnsupportedDriverActionException $ex) {
            return false;
        }
    }

    public function getFormat()
    {
        return 'png';
    }
}
