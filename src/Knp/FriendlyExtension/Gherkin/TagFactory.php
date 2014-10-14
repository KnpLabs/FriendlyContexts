<?php

namespace Knp\FriendlyExtension\Gherkin;

use Knp\FriendlyExtension\Gherkin\Tag;

class TagFactory
{
    public function create($name)
    {
        return new Tag($name);
    }
}
