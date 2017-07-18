<?php

namespace spec\Knp\FriendlyContexts\Builder;

use GuzzleHttp\ClientInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\RequestInterface;

class PostRequestBuilderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Builder\PostRequestBuilder');
    }

    function it_is_request_builder()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Builder\RequestBuilderInterface');
    }

    function it_build_a_post_request(ClientInterface $client, RequestInterface $request)
    {
        $client->request(
            'POST',
            '/resource?foo=bar',
            [
                'some options',
                'headers' => ['some headers'],
                'body' => ['data' => 'plop'],
            ]
        )->shouldBeCalled(1)->willReturn($request);

        $this->setClient($client);

        $this->build(
            '/resource',
            ['foo' => 'bar'],
            ['some headers'],
            ['data' => 'plop'],
            null,
            ['some options']
        )->shouldReturn($request);
    }
}
