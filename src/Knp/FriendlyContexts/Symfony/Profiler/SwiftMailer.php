<?php

namespace Knp\FriendlyContexts\Symfony\Profiler;

class SwiftMailer extends Collector
{
    protected $messages;

    public function getMessages()
    {
        $this->messages = [];
        $collectors = $this->getCollectors('swiftmailer', $this->getWatchableTokens());

        foreach ($collectors as $collector) {
            $this->messages = array_merge($this->messages, $collector->getMessages());
        }

        return $this->messages;
    }
}
