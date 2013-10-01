<?php

namespace spec\Knp\FriendlyContexts\Symfony\Profiler;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CollectorSpec extends ObjectBehavior
{

    /**
     * @param Symfony\Component\HttpKernel\Profiler\Profiler $profiler
     **/
    function let($profiler)
    {
        $this->beConstructedWith($profiler);

        $tokens = [
            [ 'token' => 'AZERTY' ],
            [ 'token' => 'QWERTY' ],
            [ 'token' => 'ABCDEF' ],
        ];

        $profiler->find('', '', 100, '')->willReturn($tokens);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\FriendlyContexts\Symfony\Profiler\Collector');
    }

    function it_should_return_collection_of_watchable_tokens()
    {
        $expected = [ 'AZERTY', 'QWERTY', 'ABCDEF' ];

        $this->getWatchableTokens()->shouldReturn($expected);
    }

    function it_should_reset_watchable_tokens()
    {
        $this->flush();

        $this->getWatchableTokens()->shouldReturn([]);
    }

    function it_should_return_new_tokens_after_reset($profiler)
    {
        $this->flush();

        $tokens = [
            [ 'token' => 'AZERTY' ],
            [ 'token' => 'QWERTY' ],
            [ 'token' => 'ABCDEF' ],
            [ 'token' => 'ZYXWVU' ],
        ];
        $profiler->find('', '', 100, '')->willReturn($tokens);

        $this->getWatchableTokens()->shouldReturn(['ZYXWVU']);
    }

    function it_should_return_new_tokens_after_rolling_list($profiler)
    {
        $this->flush();

        $tokens = [
            [ 'token' => 'QWERTY' ],
            [ 'token' => 'ABCDEF' ],
            [ 'token' => 'ZYXWVU' ],
        ];
        $profiler->find('', '', 100, '')->willReturn($tokens);

        $this->getWatchableTokens()->shouldReturn(['ZYXWVU']);
    }
}
