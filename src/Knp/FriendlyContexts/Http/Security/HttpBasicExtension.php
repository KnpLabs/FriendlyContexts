<?php

namespace Knp\FriendlyContexts\Http\Security;

use Guzzle\Http\Client;
use Guzzle\Http\Message\Request;
use Knp\FriendlyContexts\Builder\RequestBuilder;

class HttpBasicExtension implements SecurityExtensionInterface
{
   public function secureClient(Client $client, RequestBuilder $builder)
   {
   }

    public function secureRequest(Request $request, RequestBuilder $builder)
    {
        $credentials = $builder->getCredentials();

        if (!isset($credentials['username']) || !isset($credentials['password'])) {
            throw new \RuntimeException(
                'You must specified a "username" and a "password" for the http basic authentication.'
            );
        }

        $request->setAuth($credentials['username'], $credentials['password']);
    }
}
