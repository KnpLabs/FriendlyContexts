<?php

namespace Knp\FriendlyContexts\Builder;

class PostRequestBuilder extends AbstractRequestBuilder
{
    public function build($uri = null, array $queries = null, array $headers = null, array $postBody = null, $body = null, array $options = [])
    {
        parent::build($uri, $queries, $headers, $postBody, $body, $options);

        $resource = $queries ? sprintf('%s?%s', $uri, $this->formatQueryString($queries)) : $uri;
        $postBody = $postBody ?: $body;

        return $this->getClient()->post($resource, $headers, $postBody, $options);
    }
}
