<?php

namespace Knp\FriendlyContexts\Context;

use Behat\Behat\Context\BehatContext;

class EntityContext extends BehatContext
{
    /**
     * @Given /^the following (.*)$/
     */
    public function theFollowing($name, TableNode $table)
    {
        $rows = $table->getRows();
        $headers = array_shift($rows);
    }
}
