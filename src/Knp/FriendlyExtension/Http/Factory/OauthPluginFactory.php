<?php

namespace Knp\FriendlyExtension\Http\Factory;

use Guzzle\Plugin\Oauth\OauthPlugin;

class OauthPluginFactory
{
    public function create(array $data = [])
    {
        return new OauthPlugin($data);
    }
}
