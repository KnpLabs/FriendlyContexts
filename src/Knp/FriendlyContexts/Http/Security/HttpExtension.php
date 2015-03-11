<?php

namespace Knp\FriendlyContexts\Http\Security;

use Guzzle\Http\Client;
use Guzzle\Http\Message\Request;
use Knp\FriendlyContexts\Builder\RequestBuilder;

class HttpExtension extends HttpBasicExtension implements SecurityExtensionInterface
{
    protected $schema;

    /**
     * @param string $scheme (allowed values: basic, digest, ntlm, any)
     */
    public function __construct($scheme = 'basic')
    {
        $this->scheme = $scheme;
    }

    /**
     * {@inheritdoc}
     */
    public function secureClient(Client $client, RequestBuilder $builder)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function secureRequest(Request $request, RequestBuilder $builder)
    {
        $credentials = $builder->getCredentials();

        if (!isset($credentials['username']) || !isset($credentials['password'])) {
            throw new \RuntimeException(
                'You must specified a "username" and a "password" for the http basic authentication.'
            );
        }

        $request->setAuth($credentials['username'], $credentials['password'], $this->scheme);
    }
}
