<?php

namespace spec\Knp\FriendlyExtension\Builder;

use Guzzle\Http\ClientInterface;
use Guzzle\Http\Message\RequestInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PostRequestBuilderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyExtension\Builder\PostRequestBuilder');
    }

    function it_is_request_builder()
    {
        $this->shouldHaveType('Knp\FriendlyExtension\Builder\RequestBuilderInterface');
    }

    function it_build_a_post_request(ClientInterface $client, RequestInterface $request)
    {
        $client->post(
            '/resource?foo=bar',
            ['some headers'],
            ['data' => 'plop'],
            ['some options']
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
