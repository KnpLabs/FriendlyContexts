<?php

namespace Knp\FriendlyContexts\Http\Factory;

use GuzzleHttp\Subscriber\Oauth\Oauth1;

class OauthPluginFactory
{
    public function create(array $data = [])
    {
        return new Oauth1($data);
    }
}
