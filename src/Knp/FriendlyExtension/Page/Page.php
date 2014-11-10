<?php

namespace Knp\FriendlyExtension\Page;

use Behat\Mink\Element\DocumentElement;

class Page extends DocumentElement
{
    public $path;

    public function getPath()
    {
        return $this->path;
    }
}
