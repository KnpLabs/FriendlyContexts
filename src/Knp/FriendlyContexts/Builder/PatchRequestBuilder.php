<?php

namespace Knp\FriendlyContexts\Builder;

class PatchRequestBuilder extends AbstractRequestBuilder
{
    public function build($uri = null, array $queries = null, array $headers = null, array $postBody = null, $body = null, array $options = [])
    {
        parent::build($uri, $queries, $headers, $postBody, $body, $options);

        if (is_array($body)) {
            // format the body request to a corect x-www-form-urlencoded
            $body = $this->formatQueryString($body);
            // Set a defaut form content type
            $headers = $headers ?: [];
            $headers['Content-Type'] = 'application/x-www-form-urlencoded';
        }

        $resource = $queries ? sprintf('%s?%s', $uri, $this->formatQueryString($queries)) : $uri;

        return $this->getClient()->patch($resource, $headers, $body, $options);
    }
}
