<?php

namespace spec\Knp\FriendlyContexts\Http\Security;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Guzzle\Http\Client;
use Knp\FriendlyContexts\Builder\RequestBuilder;
use Guzzle\Http\Message\Request;

class HttpExtensionSpec extends ObjectBehavior
{
    function it_is_a_security_extension()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Http\Security\SecurityExtensionInterface');
    }

    function it_failed_if_we_dont_precised_valid_credentials(Request $request, RequestBuilder $builder)
    {
        $this->shouldThrow('RuntimeException')->duringSecureRequest($request, $builder);

        $builder->getCredentials()->shouldBeCalled()->willReturn([
            'bad_one' => 'some values',
            'other_bad_one' => 'some other values'
        ]);

        $this->shouldThrow('RuntimeException')->duringSecureRequest($request, $builder);
    }

    function it_set_up_request_basic_authentication_by_default(Request $request, RequestBuilder $builder)
    {
        $request->setAuth('username', 'password', 'basic')->shouldBeCalled(1);

        $builder->getCredentials()->shouldBeCalled()->willReturn([
            'username' => 'username',
            'password' => 'password'
        ]);

        $this->secureRequest($request, $builder);
    }

    function it_set_up_request_custom_authentication_when_provided(Request $request, RequestBuilder $builder)
    {
        $this->beConstructedWith('digest');
        $request->setAuth('username', 'password', 'digest')->shouldBeCalled(1);

        $builder->getCredentials()->shouldBeCalled()->willReturn([
            'username' => 'username',
            'password' => 'password'
        ]);

        $this->secureRequest($request, $builder);
    }

    function it_does_nothing_when_we_called_secure_client(Client $client, RequestBuilder $builder)
    {
        $this->secureClient($client, $builder)->shouldReturn(null);
    }
}
