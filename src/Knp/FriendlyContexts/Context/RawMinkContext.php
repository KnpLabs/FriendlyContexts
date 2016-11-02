<?php

namespace Knp\FriendlyContexts\Context;

use Behat\Mink\Mink;
use Behat\MinkExtension\Context\MinkAwareContext;

abstract class RawMinkContext extends Context implements MinkAwareContext
{
    private $mink;

    private $minkParameters;

    public function setMink(Mink $mink)
    {
        $this->mink = $mink;
    }

    public function getMink()
    {
        return $this->mink;
    }

    public function setMinkParameters(array $parameters)
    {
        $this->minkParameters = $parameters;
    }

    public function getMinkParameter($offset)
    {
       if (!isset($this->minkParameters[$offset])) {
            throw new \Exception(sprintf(
                'Invalid mink parameter "%s".',
                $offset
            ));
        }

        return $this->minkParameters[$offset];
    }

    public function getSession($name = null)
    {
        return $this->getMink()->getSession($name);
    }

    public function assertSession($name = null)
    {
        return $this->getMink()->assertSession($name);
    }

    public function locatePath($path)
    {
        $startUrl = rtrim($this->getMinkParameter('base_url'), '/') . '/';

        return 0 !== strpos($path, 'http') ? $startUrl . ltrim($path, '/') : $path;
    }

    protected function searchElement($locator, $element, $filterCallback = null, TraversableElement $parent = null)
    {
        $parent  = $parent ?: $this->getSession()->getPage();
        $locator = $this->fixStepArgument($locator);

        $elements = $parent->findAll('named', array(
            $element, $locator
        ));

        if (null !== $filterCallback && is_callable($filterCallback)) {
            $elements = array_values(array_filter($elements, $filterCallback));
        }

        return $elements;
    }

    protected function elementAction($locator, $element, $nbr = 1, $actionCallback, $filterCallback = null)
    {
        $elements = $this->searchElement($locator, $element, $filterCallback);

        $nbr = is_numeric($nbr) ? intval($nbr) : $nbr;
        $nbr = is_string($nbr) ? 1 : (-1 === $nbr ? count($elements) : $nbr);

        if ($nbr > count($elements)) {
            throw new ElementNotFoundException($this->getSession(), $element, null, $locator);
        }

        $e = $elements[$nbr - 1];

        $actionCallback($e);
    }

    protected function getAsserter()
    {
        return new Asserter(new TextFormater);
    }
}
