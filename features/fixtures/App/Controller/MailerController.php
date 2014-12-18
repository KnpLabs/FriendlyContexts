<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class MailerController extends Controller
{
    public function noEmailsAction()
    {
        return new Response();
    }

    public function emailWithSubjectAction()
    {
        $this->get('mailer')->send(\Swift_Message::newInstance()
            ->setSubject('Hello Subject')
            ->setFrom('send@example.com')
            ->setTo('recipient@example.com')
            ->setBody('Hello Body.')
        );

        return new Response();
    }
}
