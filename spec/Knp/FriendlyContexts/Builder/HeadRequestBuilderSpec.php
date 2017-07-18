<?php

namespace spec\Knp\FriendlyContexts\Builder;

use GuzzleHttp\ClientInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\RequestInterface;

class HeadRequestBuilderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Builder\HeadRequestBuilder');
    }

    function it_is_a_builder()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Builder\RequestBuilderInterface');
    }

    function it_build_a_head_request(ClientInterface $client, RequestInterface $request)
    {
        $client->request(
            'HEAD',
            '/resource?foo=bar',
            [
                'some options',
                'headers' => ['some headers'],
                'body' => null,
            ]
        )->shouldBeCalled(1)->willReturn($request);

        $this->setClient($client);

        $this->build(
            '/resource',
            ['foo' => 'bar'],
            ['some headers'],
            null,
            null,
            ['some options']
        )->shouldReturn($request);
    }
}
