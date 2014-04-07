<?php

namespace Knp\FriendlyContexts\Builder;

use Guzzle\Http\ClientInterface;

class GetRequestBuilder extends AbstractRequestBuilder
{
    public function build($uri = null, array $queries = null, array $headers = null, array $postBody = null, $body = null, array $options = [])
    {
        parent::build($uri, $queries, $headers, $postBody, $body, $options);

        $resource = $queries ?
            sprintf('%s?%s', $uri, $this->formatQueryString($queries)) :
            $uri
        ;

        return $this->client->get($resource, $headers, $options);
    }
}
