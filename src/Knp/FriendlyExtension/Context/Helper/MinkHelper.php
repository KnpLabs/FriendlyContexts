<?php

namespace Knp\FriendlyExtension\Context\Helper;

use Behat\Mink\Mink;
use Knp\FriendlyExtension\Context\Helper\AbstractHelper;

class MinkHelper extends AbstractHelper
{
    public function __construct(Mink $mink, array $minkParameters)
    {
        $this->mink           = $mink;
        $this->minkParameters = $minkParameters;
    }

    public function getName()
    {
        return 'mink';
    }

    public function getMink()
    {
        return $this->mink;
    }

    public function getMinkParameter($name)
    {
        return isset($this->minkParameters[$name]) ? $this->minkParameters[$name] : null;
    }

    public function getSession($name = null)
    {
        return $this->mink->getSession($name);
    }

    public function assertSession($name = null)
    {
        return $this->mink->assertSession($name);
    }

    public function locatePath($path)
    {
        $startUrl = rtrim($this->getMinkParameter('base_url'), '/') . '/';

        return 0 !== strpos($path, 'http') ? $startUrl . ltrim($path, '/') : $path;
    }

    public function fixStepArgument($argument)
    {
        return str_replace('\\"', '"', $argument);
    }

    public function searchElement($locator, $element, $filterCallback = null, TraversableElement $parent = null)
    {
        $parent  = $parent ?: $this->getSession()->getPage();
        $locator = $this->fixStepArgument($locator);

        $elements = $parent->findAll('named', array(
            $element, $this->getSession()->getSelectorsHandler()->xpathLiteral($locator)
        ));

        if (null !== $filterCallback && is_callable($filterCallback)) {
            $elements = array_values(array_filter($elements, $filterCallback));
        }

        return $elements;
    }

    public function elementAction($locator, $element, $nbr = 1, $actionCallback, $filterCallback = null)
    {
        $elements = $this->searchElement($locator, $element, $filterCallback);

        $nbr = is_numeric($nbr) ? intval($nbr) : $nbr;
        $nbr = is_string($nbr) ? 1 : (-1 === $nbr ? count($elements) : $nbr);

        $this
            ->getAsserter()
            ->assert(
                $nbr <= count($elements),
                sprintf('Expected to find at least %s "%s" %s, %s found', $nbr, $locator, $element, count($elements))
            )
        ;

        $e = $elements[$nbr - 1];

        $actionCallback($e);
    }
}
