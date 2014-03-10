<?php

namespace Knp\FriendlyContexts\Builder;

use Guzzle\Http\ClientInterface;
use Guzzle\Http\Message\RequestInterface;

class RequestBuilder implements RequestBuilderInterface
{
    private $method;

    private $headers;

    private $queries;

    private $body;

    private $options;

    private $postBody;

    private $uri;

    private $requestBuilders;

    static private function getAcceptedMethods()
    {
        return [
            RequestInterface::GET,
            RequestInterface::PUT,
            RequestInterface::POST,
            RequestInterface::DELETE,
            RequestInterface::HEAD,
            RequestInterface::CONNECT,
            RequestInterface::OPTIONS,
            RequestInterface::TRACE,
            RequestInterface::PATCH
        ];
    }

    public function __construct()
    {
        $this->requestBuilders = [];
    }

    public function build($uri = null, array $queries = null, array $headers = null, array $postBody = null, $body = null, array $options = null)
    {
        $this->setUri($uri ?: $this->uri);
        $this->setQueries($queries ?: $this->queries);
        $this->setHeaders($headers ?: $this->headers);
        $this->setPostBody($postBody ?: $this->postBody);
        $this->setBody($body ?: $this->body);
        $this->setOptions($options ?: $this->options);

        if (null === $this->method) {
            throw new \RuntimeException('You can\'t build a request without any methods');
        }

        if (!isset($this->requestBuilders[$this->method])) {
            throw new \RuntimeException(sprintf(
                'No RequestBuilder exists for method "%s"',
                $this->method
            ));
        }

        $request = $this->requestBuilders[$this->method]->build(
            $this->getUri(),
            $this->getQueries(),
            $this->getHeaders(),
            $this->getPostBody(),
            $this->getBody(),
            $this->getOptions()
        );

        $this->clean();

        return $request;
    }

    public function setMethod($method)
    {
        $method = strtoupper($method);

        if (!in_array($method, self::getAcceptedMethods())) {
            throw new \InvalidArgumentException(sprintf(
                'The requets method "%s" is not a valid HTTP method (valid method are "%s")',
                $method,
                implode(', ', self::getAcceptedMethods())
            ));
        }

        $this->method = $method;

        return $this;
    }

    public function addRequestBuilder(RequestBuilderInterface $builder, $method)
    {
        if (!in_array($method, self::getAcceptedMethods())) {
            throw new \InvalidArgumentException(sprintf(
                'The requets method "%s" is not a valid HTTP method (valid method are "%s")',
                $method,
                implode(', ', self::getAcceptedMethods())
            ));
        }

        if (isset($this->requestBuilders[$method])) {
            throw new \RuntimeException(sprintf(
                'The builder for method "%s" always exists',
                $method
            ));
        }

        $this->requestBuilders[$method] = $builder;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setHeaders(array $headers = null)
    {
        $this->headers = $headers;

        return $this;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function setBody($body = null)
    {
        $this->body = $body;

        return $this;
    }

    public function getQueries()
    {
        return $this->queries;
    }

    public function setQueries(array $queries = null)
    {
        $this->queries = $queries;

        return $this;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setOptions(array $options = null)
    {
        $this->options = $options;

        return $this;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function setPostBody(array $postBody = null)
    {
        $this->postBody = $postBody;

        return $this;
    }

    public function getPostBody()
    {
        return $this->postBody;
    }

    public function setUri($uri = null)
    {
        $this->uri = substr($uri, 0, 1) === '/' ?: sprintf('/%s', $uri);

        return $this;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function setClient(ClientInterface $client = null)
    {
    }

    protected function clean()
    {
        $this->uri      = null;
        $this->method   = null;
        $this->queries  = null;
        $this->body     = null;
        $this->postBody = null;
        $this->options  = null;
        $this->headers  = null;
    }
}
