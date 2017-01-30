<?php

namespace Knp\FriendlyContexts\Http\Security;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Knp\FriendlyContexts\Builder\RequestBuilder;

/**
 * @deprecated use HttpExtension. Will be removed in v1.0.0
 */
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
