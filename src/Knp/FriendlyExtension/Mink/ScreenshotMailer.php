<?php

namespace Knp\FriendlyExtension\Mink;

use Swift_Attachment;
use Swift_Mailer;

class ScreenshotMailer
{
    private $mailer;
    private $from;
    private $subject;

    public function __construct(Swift_Mailer $mailer, $from, $subject)
    {
        $this->mailer  = $mailer;
        $this->from    = $from;
        $this->subject = $subject;
    }

    public function send(array $screenshots, array $recipients)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($this->subject)
            ->setFrom($this->from)
            ->setTo($recipients)
        ;

        foreach ($screenshots as $screenshot)
        {
            $attachment = Swift_Attachment::newInstance(
                $screenshot->getContent(),
                sprintf('%s.%s', md5($screenshot->getContent()), $screenshot->getExtension()),
                $screenshot->getMimeType()
            );
            $message->attach($attachment);
        }

        $this->mailer->send($message);
    }
}
