<?php

namespace Knp\FriendlyContexts\Context;

use Behat\Gherkin\Filter\TagFilter;
use Knp\FriendlyContexts\Reader\SmartReader;

class SmartContext extends Context
{
    /**
     * @BeforeScenario
     **/
    public function test()
    {
        $gherkin = $this->get('gherkin');
    }
}
