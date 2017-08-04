<?php

namespace Knp\FriendlyContexts\Builder;

use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Message\RequestFactory;

abstract class AbstractRequestBuilder implements RequestBuilderInterface
{
    /**
     * @var RequestFactory
     */
    protected $requestFactory;

    /**
     * @var HttpClient
     */
    protected $client;

    /**
     * @param RequestFactory $requestFactory
     * @param HttpClient     $client
     */
    public function __construct(RequestFactory $requestFactory, HttpClient $client = null)
    {
        $this->requestFactory = $requestFactory;
        $this->client = $client ?: HttpClientDiscovery::find();
    }

    public function getRequestFactory()
    {
        return $this->requestFactory;
    }

    public function getClient()
    {
        return $this->client;
    }
}
