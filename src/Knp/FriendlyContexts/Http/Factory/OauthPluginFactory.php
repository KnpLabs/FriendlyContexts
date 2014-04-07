<?php

namespace Knp\FriendlyContexts\Http\Factory;

use Guzzle\Plugin\Oauth\OauthPlugin;

class OauthPluginFactory
{
    public function create(array $data = [])
    {
        return new OauthPlugin($data);
    }
}
