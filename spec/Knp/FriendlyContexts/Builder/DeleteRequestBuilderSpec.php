<?php

namespace spec\Knp\FriendlyContexts\Builder;

use GuzzleHttp\ClientInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\RequestInterface;

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
        $client->request(
            'DELETE',
            '/resource?foo=bar',
            [
                'some options',
                'headers' => ['some headers'],
                'body' => 'body',
            ]
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
        $client->request(
            'DELETE',
            '/resource?foo=bar',
            [
                'some options',
                'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
                'body' => 'foo=bar&baz=plop',
            ]
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
