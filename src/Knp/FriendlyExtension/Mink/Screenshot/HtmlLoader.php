<?php

namespace Knp\FriendlyExtension\Mink\Screenshot;

use Behat\Mink\Exception\DriverException;
use Behat\Mink\Mink;
use Knp\FriendlyExtension\Mink\Screenshot\Loader;

class HtmlLoader implements Loader
{
    public function __construct(Mink $mink)
    {
        $this->mink = $mink;
    }

    public function supports()
    {
        $driver = $this->mink->getSession()->getDriver();

        if (is_a($driver, 'Behat\Mink\Driver\Selenium2Driver')) {

            return false;
        }

        try {
            $content = $driver->getContent();

            return true;
        } catch (DriverException $e) {

            return false;
        }
    }

    public function take()
    {
        return $this->mink->getSession()->getDriver()->getContent();
    }

    public function getExtension()
    {
        return 'html';
    }

    public function getMimeType()
    {
        return 'text/html';
    }
}
