<?php

namespace Knp\FriendlyContexts\Symfony\Profiler;

use Symfony\Component\HttpKernel\Profiler\Profiler;

class Collector
{
    protected $profiler;
    protected $tokens = [];

    public function __construct(Profiler $profiler)
    {
        $this->profiler = $profiler;
        $this->tokens = [];
    }

    public function getWatchableTokens($reset = false)
    {
        $tokens = array_map(
            function ($e) { return $e['token']; },
            $this->profiler->find('', '', 100, '')
        );

        $tokens = array_diff($tokens, $this->tokens);

        if (true === $reset) {
            $this->enqueueTokens($tokens);
        }

        return array_values($tokens);
    }

    public function enqueueTokens($tokens = [])
    {
        $this->tokens = array_merge($this->tokens, $tokens);
    }
}
