<?php

namespace Knp\FriendlyExtension\Gherkin;

class Tag
{
    private $name;
    private $arguments = [];
    private $enabled   = false;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function addArgument($argument, $enabled)
    {
        $this->arguments[$argument] = $enabled;
    }

    public function getArguments()
    {
        $arguments = [];

        foreach ($this->arguments as $name => $enabled) {
            if ($enabled) {
                $arguments[] = $name;
            }
        }

        return $arguments;
    }

    public function hasArgument($argument)
    {
        if (false === isset($this->arguments[$argument])) {

            return false;
        }

        return $this->arguments[$argument];
    }

    public function revokeArgument($argument)
    {
        if (false === isset($this->arguments[$argument])) {

            return false;
        }

        return false === $this->arguments[$argument];
    }

    public function enable()
    {
        $this->enable = true;

        return $this;
    }

    public function disable()
    {
        $this->enable = false;

        return $this;
    }
}
