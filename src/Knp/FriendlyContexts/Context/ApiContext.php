<?php

namespace Knp\FriendlyContexts\Context;

class ApiContext extends Context
{
    /**
     * @Given /^I prepare a ([A-Za-z]+) request on "([^"]+)"( page)?$/
     */
    public function iPrepareRequest($method, $path, $page)
    {
        $page = (bool)$page;

        $method = strtoupper($method);
    }
}
