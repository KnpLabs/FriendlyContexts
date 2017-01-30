<?php

namespace spec\Knp\FriendlyContexts\Builder;

use GuzzleHttp\ClientInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\RequestInterface;

class OptionsRequestBuilderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Builder\OptionsRequestBuilder');
    }

    function it_is_a_builder()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Builder\RequestBuilderInterface');
    }

    function it_build_an_option_request(ClientInterface $client, RequestInterface $request)
    {
        $client->request(
            'OPTIONS',
            '/resource?foo=bar',
            [
                'some options',
                'headers' => null,
                'body' => null,
            ]
        )->shouldBeCalled(1)->willReturn($request);

        $this->setClient($client);

        $this->build(
            '/resource',
            ['foo' => 'bar'],
            null,
            null,
            null,
            ['some options']
        )->shouldReturn($request);
    }
}
