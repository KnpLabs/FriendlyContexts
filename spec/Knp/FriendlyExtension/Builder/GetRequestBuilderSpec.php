<?php

namespace spec\Knp\FriendlyExtension\Builder;

use Guzzle\Http\ClientInterface;
use Guzzle\Http\Message\RequestInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GetRequestBuilderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyExtension\Builder\GetRequestBuilder');
    }

    function it_is_a_request_builder()
    {
        $this->shouldHaveType('Knp\FriendlyExtension\Builder\RequestBuilderInterface');
    }

    function it_can_not_build_request_without_client()
    {
        $this->shouldThrow('RuntimeException')->duringBuild();
    }

    function it_build_a_get_request(ClientInterface $client, RequestInterface $request)
    {
        $client->get(
            '/resource?foo=bar&baz=foo',
            ['some headers'],
            ['some options']
        )->shouldBeCalled(1)->willReturn($request);

        $this->setClient($client);

        $this->build('/resource', ['foo' => 'bar', 'baz' => 'foo'], ['some headers'], null, null, ['some options'])->shouldReturn($request);
    }
}
