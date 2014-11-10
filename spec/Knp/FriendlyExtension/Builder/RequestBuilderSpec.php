<?php


namespace spec\Knp\FriendlyExtension\Builder;

use Guzzle\Http\Client;
use Guzzle\Http\Message\Request;
use Knp\FriendlyExtension\Builder\RequestBuilderInterface;
use Knp\FriendlyExtension\Http\Security\SecurityExtensionInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RequestBuilderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyExtension\Builder\RequestBuilder');
    }

    function it_is_a_request_builder()
    {
        $this->shouldHaveType('Knp\FriendlyExtension\Builder\RequestBuilderInterface');
    }

    function it_attach_a_request_builder_to_valid_method(RequestBuilderInterface $builder)
    {
        $this->addRequestBuilder($builder, 'GET');

        $this->shouldThrow('InvalidArgumentException')->duringAddRequestBuilder($builder, 'INVALID');

        $this->shouldThrow('RuntimeException')->duringAddRequestBuilder($builder, 'GET');
    }

    function it_contains_a_valid_http_method()
    {
        $this->setMethod('get');

        $this->getMethod()->shouldReturn('GET');

        $this->shouldThrow('InvalidArgumentException')->duringSetMethod('Invalid');
    }

    function it_build_sub_request_builders_and_clean_the_builder(
        Client $client,
        RequestBuilderInterface $builder,
        Request $request,
        SecurityExtensionInterface $extension
    )
    {
        $this->setMethod('GET');
        $this->setUri('/some/resource');
        $this->setQueries(['a' => 'b']);
        $this->setHeaders(['foo' => 'bar']);
        $this->setPostBody(['baz' => 'bar']);
        $this->setBody('body');
        $this->setCookies(['plop' => 'foo']);
        $this->setOptions(['some options']);
        $this->setCredentials([
            'username' => 'john',
            'password' => 'johnpass'
        ]);
        $this->addSecurityExtension($extension);

        $builder->getClient()->shouldBeCalled()->willReturn($client);

        $extension->secureClient($client, $this)->shouldBeCalled();
        $extension->secureRequest($request, $this)->shouldBeCalled();

        $builder->build(
            'some/resource',
            ['a' => 'b'],
            ['foo' => 'bar'],
            ['baz' => 'bar'],
           'body',
            ['some options']
        )->shouldBeCalled(1)->willReturn($request);

        $request->addCookie('plop', 'foo')->shouldBeCalled(1);

        $this->addRequestBuilder($builder, 'GET');

        $this->build();

        $this->getMethod()->shouldReturn(null);
        $this->getUri()->shouldReturn(null);
        $this->getQueries()->shouldReturn(null);
        $this->getHeaders()->shouldReturn(null);
        $this->getPostBody()->shouldReturn(null);
        $this->getBody()->shouldReturn(null);
        $this->getCookies()->shouldReturn(null);
        $this->getOptions()->shouldReturn([]);
        $this->getSecurityExtensions()->shouldReturn([]);
        $this->getCredentials()->shouldReturn([]);
    }
}
