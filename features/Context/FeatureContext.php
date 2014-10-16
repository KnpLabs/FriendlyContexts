<?php

namespace Context;

use Knp\FriendlyExtension\Context\Context;

class FeatureContext extends Context
{
    /**
     * @Given I wait
     */
    public function wait($seconds = 2)
    {
        $this->get('mink')->getSession()->wait($seconds * 1000);
    }
}
