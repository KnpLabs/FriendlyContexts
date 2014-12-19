<?php

namespace Page;

use Knp\FriendlyExtension\Page\Page;

class NoEmailsPage extends Page
{
    public function getPath()
    {
        return '/mailer/no-emails';
    }
}
