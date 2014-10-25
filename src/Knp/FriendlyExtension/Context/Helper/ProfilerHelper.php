<?php

namespace Knp\FriendlyExtension\Context\Helper;

use Knp\FriendlyExtension\Context\Helper\AbstractHelper;
use Symfony\Component\HttpKernel\Profiler\Profiler;

class ProfilerHelper extends AbstractHelper
{
    private $profiler;

    public function __construct(Profiler $profiler)
    {
        $this->profiler = $profiler;
    }

    public function getProfile($token = null)
    {
        if (null === $token) {
            $headers = $this->get('mink')->getSession()->getResponseHeaders();

            if (!isset($headers['X-Debug-Token']) && !isset($headers['x-debug-token'])) {
                throw new \RuntimeException('Debug-Token not found in response headers. Have you turned on the debug flag?');
            }

            $token = isset($headers['X-Debug-Token']) ? $headers['X-Debug-Token'] : $headers['x-debug-token'];
            if (is_array($token)) {
                $token = end($token);
            }
        }

        return $this->profiler->loadProfile($token);
    }

    public function getCollector($name, $token = null)
    {
        return $this->getProfile($token)->getCollector($name);
    }
}
