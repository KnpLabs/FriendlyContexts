<?php

namespace spec\Knp\FriendlyExtension\Http;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class HttpContentTypeGuesserSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyExtension\Http\HttpContentTypeGuesser');
    }

    function it_guess_a_short_content_type()
    {
        $this->guess('json')->shouldReturn(['application/json']);
        $this->guess('xml')->shouldReturn(['application/xml', 'text/xml']);
        $this->guess('html')->shouldReturn(['text/html', 'application/xhtml+xml']);
    }

    function it_test_is_a_short_type_exists()
    {
        $this->exists('plop')->shouldReturn(false);
        $this->exists('wave')->shouldReturn(true);
    }

    function it_guess_a_content_type()
    {
        $this->getKey('application/json')->shouldReturn('json');
        $this->getKey('application/xml')->shouldReturn('xml');
        $this->getKey('text/xml')->shouldReturn('xml');
        $this->getKey('text/html')->shouldReturn('html');
    }

    function it_throw_an_error_on_non_existent_short_type_guessing()
    {
        $this->shouldThrow('InvalidArgumentException')->duringGuess('plop');
    }
}
