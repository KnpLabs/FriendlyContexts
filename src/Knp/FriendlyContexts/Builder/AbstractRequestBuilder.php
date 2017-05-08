<?php

namespace Knp\FriendlyContexts\Builder;

use Http\Message\MessageFactory;

abstract class AbstractRequestBuilder implements RequestBuilderInterface
{
    /**
     * @var MessageFactory
     */
    private $messageFactory;

    public function build($uri = null, array $queries = null, array $headers = null, array $postBody = null, $body = null, array $options = [])
    {
        if (null === $this->messageFactory) {
            throw new \RuntimeException('You must precised a valid message factory before build a request');
        }
    }

    public function setMessageFactory(MessageFactory $messageFactory)
    {
        $this->messageFactory = $messageFactory;

        return $this;
    }

    public function getMessageFactory()
    {
        return $this->messageFactory;
    }
}
