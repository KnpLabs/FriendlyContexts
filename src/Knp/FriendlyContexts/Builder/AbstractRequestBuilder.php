<?php

namespace Knp\FriendlyContexts\Builder;

use Guzzle\Http\ClientInterface;

abstract class AbstractRequestBuilder implements RequestBuilderInterface
{
    private $client;

    public function build($uri = null, array $queries = null, array $headers = null, array $postBody = null, $body = null, array $options = [])
    {
        if (null === $this->client) {
            throw new \RuntimeException('You must precised a valid client before build a request');
        }
    }

    public function setClient(ClientInterface $client = null)
    {
        $this->client = $client;
    }

    /**
     * Return a clone of guzzle client if available.
     *
     * This is useful to ensure executions isolation.
     *
     * @return ClientInterface|null
     */
    public function getClient()
    {
        if ($this->client instanceof ClientInterface) {
            return clone $this->client;
        }
    }

    protected function formatQueryString(array $queries = null)
    {
        if (null === $queries) {
            return;
        }

        return http_build_query($queries);
    }
}
