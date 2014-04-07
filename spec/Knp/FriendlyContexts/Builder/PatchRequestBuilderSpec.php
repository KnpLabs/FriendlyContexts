<?php

namespace spec\Knp\FriendlyContexts\Builder;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Guzzle\Http\ClientInterface;
use Guzzle\Http\Message\RequestInterface;

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
        $client->patch(
            '/resource?foo=bar',
            ['some headers'],
            'body datas',
            ['some options']
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
        $client->patch(
            '/resource',
            ['Content-Type' => 'application/x-www-form-urlencoded'],
            'foo=bar&baz=plop',
            ['some options']
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
