<?php

namespace spec\Knp\FriendlyContexts\Http;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class HttpContentTypeGuesserSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Http\HttpContentTypeGuesser');
    }

    function it_guess_a_short_content_type()
    {
        $this->guess('json')->shouldReturn(['application/json']);
        $this->guess('xml')->shouldReturn(['application/xml', 'text/xml']);
        $this->guess('html')->shouldReturn(['application/xhtml+xml', 'text/html']);
    }

    function it_test_is_a_short_type_exists()
    {
        $this->exists('plop')->shouldReturn(false);
        $this->exists('wave')->shouldReturn(true);
    }

    function it_throw_an_error_on_non_existent_short_type_guessing()
    {
        $this->shouldThrow('InvalidArgumentException')->duringGuess('plop');
    }
}
