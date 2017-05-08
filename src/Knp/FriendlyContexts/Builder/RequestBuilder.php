<?php

namespace Knp\FriendlyContexts\Builder;

use Guzzle\Http\ClientInterface;
use Guzzle\Http\Message\RequestInterface;
use Http\Message\MessageFactory;
use Knp\FriendlyContexts\Http\Security\SecurityExtensionInterface;

class RequestBuilder implements RequestBuilderInterface
{
    /**
     * @var MessageFactory
     */
    private $messageFactory;

    /**
     * @var string
     */
    private $method;

    /**
     * @var string[]
     */
    private $headers;

    /**
     * @var string[]
     */
    private $queries;

    /**
     * @var string|array
     */
    private $body;

    /**
     * @var array
     */
    private $options;

    private $postBody;

    private $cookies;

    /**
     * @var SecurityExtensionInterface[]
     */
    private $securityExtensions;

    private $credentials;

    private $uri;

    private $requestBuilders;

    private $files;

    private static function getAcceptedMethods()
    {
        return [
            'GET',
            'PUT',
            'POST',
            'DELETE',
            'HEAD',
            'OPTIONS',
            'PATCH'
        ];
    }

    public function __construct()
    {
        $this->requestBuilders    = [];
        $this->options            = [];
        $this->securityExtensions = [];
        $this->credentials        = [];
        $this->files              = [];
    }

    public function build($uri = null, array $queries = null, array $headers = null, array $postBody = null, $body = null, array $options = [])
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

        $client = $this->requestBuilders[$this->method]->getClient();

        foreach ($this->securityExtensions as $extension) {
            $extension->secure($this);
        }

        $request = $this->messageFactory->createRequest(
            $this->method,
            $this->getUri(),
            $this->getHeaders()
        );
            ,
            $this->getQueries(),
            ,
            $this->getPostBody(),
            $this->getBody(),
            $this->getOptions()
        );

        if (null !== $this->cookies) {
            foreach ($this->cookies as $name => $cookie) {
                $request->addCookie($name, $cookie);
            }
        }

        foreach ($this->securityExtensions as $extension) {
            $extension->secureRequest($request, $this);
        }

        foreach ($this->files as $file) {
            $request->addPostFile($file['name'], $file['path']);
        }

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

    public function getCookies()
    {
        return $this->cookies;
    }

    public function setCookies(array $cookies = null)
    {
        $this->cookies = $cookies;

        return $this;
    }

    public function addSecurityExtension(SecurityExtensionInterface $extension)
    {
        trigger_error("Deprecated. To be remove in 1.0. Use `setSecurity` instead.", E_USER_DEPRECATED);
        $this->securityExtensions[] = $extension;

        return $this;
    }

    public function setSecurity(SecurityExtensionInterface $extension)
    {
        $this->securityExtensions = [$extension];
    }

    /**
     * @return SecurityExtensionInterface[]
     */
    public function getSecurityExtensions()
    {
        return $this->securityExtensions;
    }

    public function getCredentials()
    {
        return $this->credentials;
    }

    public function setCredentials(array $credentials)
    {
        $this->credentials = $credentials;

        return $this;
    }

    public function setUri($uri = null)
    {
        $this->uri = substr($uri, 0, 1) === '/' ? substr($uri, 1) : $uri;

        return $this;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function addFile($name, $path)
    {
        $this->files[] = ['name' => $name, 'path' => $path];

        return $this;
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

    protected function clean()
    {
        $this->uri                = null;
        $this->method             = null;
        $this->queries            = null;
        $this->body               = null;
        $this->postBody           = null;
        $this->cookies            = null;
        $this->headers            = null;
        $this->options            = [];
        $this->securityExtensions = [];
        $this->credentials        = [];
        $this->files              = [];
    }
}
