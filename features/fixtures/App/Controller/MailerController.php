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
}
