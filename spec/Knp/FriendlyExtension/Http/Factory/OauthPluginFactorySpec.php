<?php

namespace spec\Knp\FriendlyExtension\Http\Factory;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class OauthPluginFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyExtension\Http\Factory\OauthPluginFactory');
    }

    function it_create_an_oauth_plugin()
    {
        $this->create(['some datas'])->shouldHaveType('Guzzle\Plugin\Oauth\OauthPlugin');
    }
}
