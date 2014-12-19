<?php

namespace Knp\FriendlyExtension\Context\Helper;

use Knp\FriendlyExtension\Context\Helper\AbstractHelper;

class SwiftMailerHelper extends AbstractHelper
{
    public function countEmailsSent()
    {
        return $this->getCollector()->getMessageCount();
    }

    public function isEmailSent($subject = null, $to = null)
    {
        foreach ($this->getCollector()->getMessages('default') as $message) {
            if ((null === $subject || $subject === trim($message->getSubject())) &&
                (null === $to || array_key_exists($to, $message->getTo()))
            ) {
                return true;
            }
        }

        return false;
    }

    protected function getCollector()
    {
        return $this->get('profiler')->getCollector('swiftmailer');
    }

    public function getName()
    {
        return 'swiftmailer';
    }
}
