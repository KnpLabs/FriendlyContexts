<?php

namespace Knp\FriendlyContexts\Builder;

class OptionsRequestBuilder extends AbstractRequestBuilder
{
    public function build($uri = null, array $queries = null, array $headers = null, array $postBody = null, $body = null, array $options = [])
    {
        parent::build($uri, $queries, $headers, $postBody, $body, $options);

        $resource = $queries ?
            sprintf('%s?%s', $uri, $this->formatQueryString($queries)) :
            $uri
        ;

        $options['headers'] = $headers;
        $options['body'] = $body;

        return $this->getClient()->request('OPTIONS', $resource, $options);
    }
}
