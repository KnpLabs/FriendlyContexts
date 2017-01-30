<?php

namespace spec\Knp\FriendlyContexts\Http\Security;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Knp\FriendlyContexts\Http\Factory\OauthPluginFactory;
use Knp\FriendlyContexts\Builder\RequestBuilder;

class OauthExtensionSpec extends ObjectBehavior
{
    function let(OauthPluginFactory $factory, Oauth1 $plugin)
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

    function it_set_up_a_client_oauth_plugin_subscriber(Client $client, RequestBuilder $builder, $factory, $plugin, HandlerStack $handlerStack)
    {
        $builder->getCredentials()->willReturn([
            'some builder credentials'
        ]);

        $factory->create([
            'some builder credentials'
        ])->shouldBeCalled()->willReturn($plugin);

        $handlerStack->push($plugin)->shouldBeCalled();

        $client->getConfig()->shouldBeCalled()->willReturn(['handler' => $handlerStack]);

        $this->secureClient($client, $builder);
    }
}
