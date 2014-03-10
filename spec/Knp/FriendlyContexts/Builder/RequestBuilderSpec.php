<?php

namespace spec\Knp\FriendlyContexts\Builder;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Knp\FriendlyContexts\Builder\RequestBuilderInterface;

class RequestBuilderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Builder\RequestBuilder');
    }

    function it_is_a_request_builder()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Builder\RequestBuilderInterface');
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

    function it_build_sub_request_builders_and_clean_the_builder(RequestBuilderInterface $builder)
    {
        $this->setMethod('GET');
        $this->setUri('/some/resource');
        $this->setQueries(['a' => 'b']);
        $this->setHeaders(['foo' => 'bar']);
        $this->setPostBody(['baz' => 'bar']);
        $this->setBody('body');
        $this->setOptions(['some options']);

        $builder->build(
            '/some/resource',
            ['a' => 'b'],
            ['foo' => 'bar'],
            ['baz' => 'bar'],
            'body',
            ['some options']
        )->shouldBeCalled(1);

        $this->addRequestBuilder($builder, 'GET');

        $this->build();

        $this->getMethod()->shouldReturn(null);
        $this->getUri()->shouldReturn(null);
        $this->getQueries()->shouldReturn(null);
        $this->getHeaders()->shouldReturn(null);
        $this->getPostBody()->shouldReturn(null);
        $this->getBody()->shouldReturn(null);
        $this->getOptions()->shouldReturn(null);
    }
}
