<?php

namespace Page;

use Knp\FriendlyExtension\Page\Page;

class EmailPage extends Page
{
    public function getPath()
    {
        return '/mailer/email';
    }
}
