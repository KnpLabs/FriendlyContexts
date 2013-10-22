<?php

namespace Knp\FriendlyContexts\Symfony\Profiler;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SwiftMailer extends Collector
{
    protected $messages;

    public function getMessages()
    {
        $this->messages = [];
        $collectors = $this->getCollectors('swiftmailer', $this->getWatchableTokens());

        foreach ($collectors as $collector) {
            $collector->collect(new Request, new Response);
            $this->messages = array_merge($this->messages, $collector->getMessages());
        }

        return $this->messages;
    }
}
