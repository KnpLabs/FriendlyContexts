<?php

namespace spec\Knp\FriendlyContexts\Builder;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Guzzle\Http\ClientInterface;
use Guzzle\Http\Message\RequestInterface;

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
        $client->head(
            '/resource?foo=bar',
            ['some headers'],
            ['some options']
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
