<?php

namespace Knp\FriendlyContexts\Http\Security;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use Knp\FriendlyContexts\Builder\RequestBuilder;
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

        /** @var HandlerStack $handler */
        $handler = $client->getConfig()['handler'];
        $handler->push($plugin);
    }

    public function secureRequest(Request $request, RequestBuilder $builder)
    {
    }
}
