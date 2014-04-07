<?php

namespace Knp\FriendlyContexts\Http\Security;

use Guzzle\Http\Message\Request;
use Knp\FriendlyContexts\Builder\RequestBuilder;
use Guzzle\Http\Client;
use Knp\FriendlyContexts\Http\Factory\OauthPluginFactory;

class OauthExtension implements SecurityExtensionInterface
{
    private $oauthPluginFactory;

    public function __construct(OauthPluginFactory $factory = null)
    {
        $this->oauthPluginFactory = $factory ?: new OauthPluginFactory;
    }

    public function secureClient(Client $client, RequestBuilder $builder)
    {
        $plugin = $this->oauthPluginFactory->create($builder->getCredentials());

        $client->addSubscriber($plugin);
    }

    public function secureRequest(Request $request, RequestBuilder $builder)
    {
    }
}
