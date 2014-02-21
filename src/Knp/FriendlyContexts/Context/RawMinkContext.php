<?php

namespace Knp\FriendlyContexts\Context;

use Behat\Mink\Mink;
use Behat\MinkExtension\Context\MinkAwareContext;

abstract class RawMinkContext extends Context implements MinkAwareContext
{
    private $mink;

    private $minkParameters;

    public function setMink(Mink $mink)
    {
        $this->mink = $mink;
    }

    public function getMink()
    {
        return $this->mink;
    }

    public function setMinkParameters(array $parameters)
    {
        $this->minkParameters = $parameters;
    }

    public function getMinkParameter($offset)
    {
       if (!isset($this->minkParameters[$offset])) {
            throw new \Exception(sprintf(
                'Invalid mink parameter "%s".',
                $offset
            ));
        }

        return $this->minkParameters[$offset];
    }

    public function getSession($name = null)
    {
        return $this->getMink()->getSession($name);
    }

    public function assertSession($name = null)
    {
        return $this->getMink()->assertSession($name);
    }

    public function locatePath($path)
    {
        $startUrl = rtrim($this->getMinkParameter('base_url'), '/') . '/';

        return 0 !== strpos($path, 'http') ? $startUrl . ltrim($path, '/') : $path;
    }
}
