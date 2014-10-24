<?php

namespace Knp\FriendlyExtension\Mink\Screenshot;

use Behat\Mink\Driver\Selenium2Driver;
use Behat\Mink\Mink;
use Knp\FriendlyExtension\Mink\Screenshot\Loader;

class PngLoader implements Loader
{
    public function __construct(Mink $mink)
    {
        $this->mink = $mink;
    }

    public function supports()
    {
        return true === $this
            ->mink
            ->getSession()
            ->getDriver() instanceof Selenium2Driver
        ;
    }

    public function take()
    {
        return $this->mink->getSession()->getScreenshot();
    }

    public function getExtension()
    {
        return 'png';
    }

    public function getMimeType()
    {
        return 'image/png';
    }
}
