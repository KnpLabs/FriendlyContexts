<?php

namespace Knp\FriendlyContexts\Http\Security;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Knp\FriendlyContexts\Builder\RequestBuilder;

interface SecurityExtensionInterface
{
    public function secureClient(Client $client, RequestBuilder $builder);

    public function secureRequest(Request $request, RequestBuilder $builder);
}
