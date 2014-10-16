<?php

namespace Knp\FriendlyExtension\Context\Helper;

use Behat\Mink\Mink;
use Knp\FriendlyExtension\Context\Helper\AbstractHelper;

class MinkHelper extends AbstractHelper
{
    public function __construct(Mink $mink, array $minkParameters)
    {
        $this->mink           = $mink;
        $this->minkParameters = $minkParameters;
    }

    public function getName()
    {
        return 'mink';
    }

    public function getMink()
    {
        return $this->mink;
    }

    public function getMinkParameter($name)
    {
        return isset($this->minkParameters[$name]) ? $this->minkParameters[$name] : null;
    }

    public function getSession($name = null)
    {
        return $this->mink->getSession($name);
    }

    public function assertSession($name = null)
    {
        return $this->mink->assertSession($name);
    }

    public function locatePath($path)
    {
        $startUrl = rtrim($this->getMinkParameter('base_url'), '/') . '/';

        return 0 !== strpos($path, 'http') ? $startUrl . ltrim($path, '/') : $path;
    }
}
