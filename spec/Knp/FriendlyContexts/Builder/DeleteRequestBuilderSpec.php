<?php

namespace spec\Knp\FriendlyContexts\Builder;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Guzzle\Http\ClientInterface;
use Guzzle\Http\Message\RequestInterface;

class DeleteRequestBuilderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Builder\DeleteRequestBuilder');
    }

    function it_is_a_request_builder()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Builder\RequestBuilderInterface');
    }

    function it_build_a_delete_request(ClientInterface $client, RequestInterface $request)
    {
        $client->delete(
            '/resource?foo=bar',
            ['some headers'],
            'body',
            ['some options']
        )->shouldBeCalled(1)->willReturn($request);

        $this->setClient($client);

        $this->build(
            '/resource',
            ['foo' => 'bar'],
            ['some headers'],
            null,
            'body',
            ['some options']
        )->shouldReturn($request);
    }

    function it_format_body_to_a_valid_form_urlencod_request(ClientInterface $client, RequestInterface $request)
    {
        $client->delete(
            '/resource?foo=bar',
            ['Content-Type' => 'application/x-www-form-urlencoded'],
            'foo=bar&baz=plop',
            ['some options']
        )->shouldBeCalled(1)->willReturn($request);

        $this->setClient($client);

        $this->build(
            '/resource',
            ['foo' => 'bar'],
            null,
            null,
            ['foo' => 'bar', 'baz' => 'plop'],
            ['some options']
        )->shouldReturn($request);
    }
}
