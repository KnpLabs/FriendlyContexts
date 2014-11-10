<?php

namespace Knp\FriendlyExtension\Call;

use Behat\Testwork\Call\Call;
use Behat\Testwork\Call\CallCenter as BaseCallCenter;

class CallCenter
{
    private $callCenter;

    public function __construct(BaseCallCenter $callCenter)
    {
        $this->callCenter = $callCenter;
    }

    public function makeCall(Call $call)
    {
        return $this->callCenter->makeCall($call);
    }
}
