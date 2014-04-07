<?php

namespace spec\Knp\FriendlyContexts\Http\Security;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Knp\FriendlyContexts\Http\Factory\OauthPluginFactory;
use Guzzle\Http\Message\Request;
use Knp\FriendlyContexts\Builder\RequestBuilder;
use Guzzle\Plugin\Oauth\OauthPlugin;
use Guzzle\Http\Client;

class OauthExtensionSpec extends ObjectBehavior
{
    function let(OauthPluginFactory $factory, OauthPlugin $plugin)
    {
        $this->beConstructedWith($factory);
        $factory->create(Argument::cetera())->willReturn($plugin);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Http\Security\OauthExtension');
    }

    function it_is_a_security_extension()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Http\Security\SecurityExtensionInterface');
    }

    function it_does_not_secure_the_request(Request $request, RequestBuilder $builder)
    {
        $this->secureRequest($request, $builder)->shouldReturn(null);
    }

    function it_set_up_a_client_oauth_plugin_subscriber(Client $client, RequestBuilder $builder, $factory, $plugin)
    {
        $builder->getCredentials()->willReturn([
            'some builder credentials'
        ]);

        $factory->create([
            'some builder credentials'
        ])->shouldBeCalled()->willReturn($plugin);

        $client->addSubscriber($plugin)->shouldBeCalled();

        $this->secureClient($client, $builder);
    }
}
