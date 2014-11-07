<?php

namespace Knp\FriendlyExtension\Context\Helper;

use Guzzle\Http\Exception\BadResponseException;
use Guzzle\Http\Message\RequestInterface;
use Knp\FriendlyExtension\Builder\RequestBuilderInterface;
use Knp\FriendlyExtension\Context\Helper\AbstractHelper;
use Knp\FriendlyExtension\Http\HttpContentTypeGuesser;
use Knp\FriendlyExtension\Http\Security\SecurityExtensionInterface;

class ApiHelper extends AbstractHelper
{
    private $builder;
    private $typeGuesser;
    private $response;

    public function __construct(RequestBuilderInterface $builder, HttpContentTypeGuesser $typeGuesser)
    {
        $this->builder     = $builder;
        $this->typeGuesser = $typeGuesser;
    }

    public function setCall($method, $uri)
    {
        $this
            ->builder
            ->setMethod($method)
            ->setUri($uri)
        ;

        return $this;
    }

    public function setHeaders(array $headers)
    {
        $this->builder->setHeaders($headers);

        return $this;
    }

    public function setQueries(array $queries)
    {
        $this->builder->setQueries($queries);

        return $this;
    }

    public function setBody($body)
    {
        $this->builder->setBody($body);

        return $this;
    }

    public function setCookies(array $cookies)
    {
        $this->builder->setCookies($cookies);

        return $this;
    }

    public function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }

    public function setData(array $data)
    {
        if (RequestInterface::POST === $this->builder->getMethod()) {
            $this->builder->setPostBody($data);
        } else {
            $this->builder->setBody($data);
        }

        return $this;
    }

    public function setSecurityCredentials(SecurityExtensionInterface $security, array $credentials)
    {
        $this
            ->builder
            ->setCredentials($credentials)
            ->addSecurityExtension($security)
        ;

        return $this;
    }

    public function send()
    {
        try {
            $this->response = $this->getRequestBuilder()->build()->send();
        } catch (BadResponseException $e) {
            $this->response = $e->getResponse();
        }

        return $this->response;
    }

    public function getResponse()
    {
        if (null === $this->response) {
            throw new \RuntimeException('You must send a request before testing a response.');
        }

        return $this->response;
    }

    public function guessType($type)
    {
        return $this->typeGuesser->guess($type);
    }

    public function getName()
    {
        return 'api';
    }

    public function clear()
    {
        $this->response = null;

        $this->builder->clean();
    }
}
