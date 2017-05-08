<?php

namespace Knp\FriendlyContexts\Http\Security;

use Http\Client\HttpClient;
use Knp\FriendlyContexts\Builder\RequestBuilder;
use Psr\Http\Message\RequestInterface;

interface SecurityExtensionInterface
{
    /**
     * @param RequestInterface $request
     * @param RequestBuilder   $builder
     * @return mixed
     */
    public function secureRequest(RequestInterface $request, RequestBuilder $builder);
}
