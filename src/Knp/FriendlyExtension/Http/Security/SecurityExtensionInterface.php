<?php

namespace Knp\FriendlyExtension\Http\Security;

use Guzzle\Http\Client;
use Guzzle\Http\Message\Request;
use Knp\FriendlyExtension\Builder\RequestBuilder;

interface SecurityExtensionInterface
{
    public function secureClient(Client $client, RequestBuilder $builder);

    public function secureRequest(Request $request, RequestBuilder $builder);
}
