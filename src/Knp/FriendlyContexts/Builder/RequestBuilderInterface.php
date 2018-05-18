<?php

namespace Knp\FriendlyContexts\Builder;

use Http\Message\MessageFactory;
use Psr\Http\Message\RequestInterface;

interface RequestBuilderInterface
{
    /**
     * @param string|null $uri
     * @param array|null $queries
     * @param array|null $headers
     * @param array|null $postBody
     * @param string|null $body
     * @param array $options
     * @return RequestInterface
     */
    public function build($uri = null, array $queries = null, array $headers = null, array $postBody = null, $body = null, array $options = []);

    /**
     * @param MessageFactory $messageFactory
     * @return RequestBuilderInterface
     */
    public function setMessageFactory(MessageFactory $messageFactory);

    /**
     * @return MessageFactory
     */
    public function getMessageFactory();
}
