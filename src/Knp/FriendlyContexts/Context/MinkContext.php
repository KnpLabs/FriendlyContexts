<?php

namespace Knp\FriendlyContexts\Context;

use Behat\MinkExtension\Context\MinkContext as BaseMinkContext;
use Behat\Mink\Exception\ElementNotFoundException;

class MinkContext extends BaseMinkContext
{
    public function clickLink($link)
    {
        $page  = $this->getSession()->getPage();
        $links = $page->findAll('css', 'a');

        foreach ($links as $element) {
            if ($link=== $element->getText()) {
                $element->click();
                return;
            }
        }

        throw new ElementNotFoundException(sprintf(
            'Link "%s" not found', $link
        ));
    }

    /**
     * @When /^(?:|I )follow the link containing "(?P<link>(?:[^"]|\\")*)"$/
     */
    public function clickLinkContaining($link)
    {
        parent::clickLink($link);
    }
}
