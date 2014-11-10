<?php

namespace spec\Knp\FriendlyExtension\Builder;

use Guzzle\Http\ClientInterface;
use Guzzle\Http\Message\RequestInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class OptionsRequestBuilderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyExtension\Builder\OptionsRequestBuilder');
    }

    function it_is_a_builder()
    {
        $this->shouldHaveType('Knp\FriendlyExtension\Builder\RequestBuilderInterface');
    }

    function it_build_an_option_request(ClientInterface $client, RequestInterface $request)
    {
        $client->options(
            '/resource?foo=bar',
            ['some options']
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
