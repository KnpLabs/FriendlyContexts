<?php

namespace Page;

use Knp\FriendlyExtension\Page\Page;

class EmailWithSubjectPage extends Page
{
    public function getPath()
    {
        return '/mailer/email-with-subject';
    }
}
