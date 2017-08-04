<?php

namespace Knp\FriendlyContexts\Builder;

use Http\Client\Common\Plugin;
use Http\Message\CookieJar;
use Http\Message\RequestFactory;
use Knp\FriendlyContexts\Http\ClientFactory;
use Knp\FriendlyContexts\Http\Security\SecurityExtensionInterface;

class RequestBuilder implements RequestBuilderInterface
{
    /**
     * @var RequestFactory
     */
    private $requestFactory;

    /**
     * @var ClientFactory
     */
    private $clientFactory;

    /**
     * @var boolean
     */
    private $keepCookies;

    /**
     * @var CookieJar
     */
    protected $cookieJar;

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

    /**
     * @var SecurityExtensionInterface[]
     */
    private $securityExtensions;

    /**
     * @var Plugin[]
     */
    private $plugins;

    private $uri;

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

    public function __construct(ClientFactory $clientFactory)
    {
        $this->clientFactory = $clientFactory;
        $this->options            = [];
        $this->securityExtensions = [];
        $this->files              = [];
        $this->keepCookies = false;
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

        // BC Layer: to be remove in 1.0
        foreach ($this->securityExtensions as $extension) {
            $extension->secure($this);
        }
        // End of BC Layer

        $request = $this->requestFactory->createRequest(
            $this->method,
            $this->getUri(),
            $this->getHeaders()
        );
//            ,
//            $this->getQueries(),
//            ,
//            $this->getPostBody(),
//            $this->getBody(),
//            $this->getOptions()
//        );
//
//        if (null !== $this->cookies) {
//            foreach ($this->cookies as $name => $cookie) {
//                $request->addCookie($name, $cookie);
//            }
//        }
//
//        foreach ($this->securityExtensions as $extension) {
//            $extension->secureRequest($request, $this);
//        }
//
//        foreach ($this->files as $file) {
//            $request->addPostFile($file['name'], $file['path']);
//        }
//
        $this->clean(); // Make the builder reusable

        return $request;
    }

    /**
     * @param Plugin $plugin
     * @return RequestBuilder
     */
    public function addPlugin(Plugin $plugin)
    {
        $this->plugins[] = $plugin;

        return $this;
    }

    /**
     * @return Plugin[]
     */
    public function getPlugins()
    {
        return $this->plugins;
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
        return $this->cookieJar->getCookies();
    }

    public function setCookies(array $cookies = null)
    {
        $this->cookieJar->setCookies($cookies);

        return $this;
    }

    /**
     * @return SecurityExtensionInterface[]
     */
    public function getSecurityExtensions()
    {
        return $this->securityExtensions;
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

    /**
     * @param boolean $keepCookies
     * @return RequestBuilder
     */
    public function keepCookies($keepCookies=true)
    {
        $this->keepCookies = $keepCookies;

        return $this;
    }

    protected function clean()
    {
        $this->uri                = null;
        $this->method             = null;
        $this->queries            = null;
        $this->body               = null;
        $this->postBody           = null;
        $this->headers            = null;
        $this->options            = [];
        $this->securityExtensions = [];
        $this->files              = [];
        $this->plugins = [];

        if (!$this->keepCookies) {
            $this->cookieJar = new CookieJar();
        }
    }

    public function addSecurityExtension(SecurityExtensionInterface $extension)
    {
        trigger_error("Deprecated. To be remove in 1.0. Use `addPlugin` instead.", E_USER_DEPRECATED);
        $this->securityExtensions[] = $extension;

        return $this;
    }

    public function setSecurity(SecurityExtensionInterface $extension)
    {
        trigger_error("Deprecated. To be remove in 1.0. Use `addPlugin` instead.", E_USER_DEPRECATED);
        $this->securityExtensions = [$extension];
    }
}
