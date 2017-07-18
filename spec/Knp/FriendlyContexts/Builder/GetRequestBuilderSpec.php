<?php

namespace spec\Knp\FriendlyContexts\Builder;

use GuzzleHttp\ClientInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\RequestInterface;

class GetRequestBuilderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Builder\GetRequestBuilder');
    }

    function it_is_a_request_builder()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Builder\RequestBuilderInterface');
    }

    function it_can_not_build_request_without_client()
    {
        $this->shouldThrow('RuntimeException')->duringBuild();
    }

    function it_build_a_get_request(ClientInterface $client, RequestInterface $request)
    {
        $client->request(
            'GET',
            '/resource?foo=bar&baz=foo',
            [
                'some options',
                'headers' => ['some headers'],
                'body' => null,
            ]
        )->shouldBeCalled(1)->willReturn($request);

        $this->setClient($client);

        $this->build('/resource', ['foo' => 'bar', 'baz' => 'foo'], ['some headers'], null, null, ['some options'])->shouldReturn($request);
    }
}
