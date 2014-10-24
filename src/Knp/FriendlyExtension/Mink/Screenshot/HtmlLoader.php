<?php

namespace Knp\FriendlyExtension\Mink\Screenshot;

use Behat\Mink\Driver\Selenium2Driver;
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
        return false === $this
            ->mink
            ->getSession()
            ->getDriver() instanceof Selenium2Driver
        ;
    }

    public function take()
    {
        return $this->mink->getSession()->getContent();
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
