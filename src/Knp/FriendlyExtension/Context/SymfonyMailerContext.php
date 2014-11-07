<?php

namespace Knp\FriendlyExtension\Context;

use Behat\Gherkin\Node\TableNode;
use Knp\FriendlyExtension\Context\Context;

class SymfonyMailerContext extends Context
{
    /**
     * @Then no email should have been sent
     */
    public function noEmailShouldHaveBeenSent()
    {
    }
}
