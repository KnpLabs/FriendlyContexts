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
            $this->profiler->find('', '', 100, '', null, null)
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

    public function flush()
    {
        $this->enqueueTokens($this->getWatchableTokens());
    }

    protected function getProfiler()
    {
        return $this->profiler;
    }

    protected function getCollectors($name, $tokens = array())
    {
        $tokens = is_string($tokens) ? [ $tokens ] : $tokens;

        return array_map(
            function ($e) use ($name) {
                return $this->getProfiler()->loadProfile($e)->getCollector($name);
            },
            $tokens
        );
    }
}
