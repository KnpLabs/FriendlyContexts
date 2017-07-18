<?php

namespace spec\Knp\FriendlyContexts\Builder;

use GuzzleHttp\ClientInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\RequestInterface;

class PatchRequestBuilderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Builder\PatchRequestBuilder');
    }

    function it_is_a_request_builder()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Builder\RequestBuilderInterface');
    }

    function it_build_a_patch_request(ClientInterface $client, RequestInterface $request)
    {
        $client->request(
            'PATCH',
            '/resource?foo=bar',
            [
                'some options',
                'headers' => ['some headers'],
                'body' => 'body datas',
            ]
        )->shouldBeCalled(1)->willReturn($request);

        $this->setClient($client);

        $this->build(
            '/resource',
            ['foo' => 'bar'],
            ['some headers'],
            null,
            'body datas',
            ['some options']
        )->shouldReturn($request);
    }

    function it_format_the_request_to_a_valid_form_urlencode(ClientInterface $client, RequestInterface $request)
    {
        $client->request(
            'PATCH',
            '/resource',
            [
                'some options',
                'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
                'body' => 'foo=bar&baz=plop',
            ]
        )->shouldBeCalled(1)->willReturn($request);

        $this->setClient($client);

        $this->build(
            '/resource',
            null,
            null,
            null,
            ['foo' => 'bar', 'baz' => 'plop'],
            ['some options']
        )->shouldReturn($request);
    }
}
