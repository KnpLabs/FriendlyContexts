<?php

namespace Page;

use Knp\FriendlyExtension\Page\Page;

class APage extends Page
{
    public function getPath()
    {
        return '/{part}/{file}.{extension}';
    }
}
