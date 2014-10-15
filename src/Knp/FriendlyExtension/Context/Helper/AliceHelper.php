<?php

namespace Knp\FriendlyExtension\Context\Helper;

use Knp\FriendlyExtension\Alice\ClassLoader;
use Knp\FriendlyExtension\Context\Helper\AbstractHelper;
use Knp\FriendlyExtension\Gherkin\TagLoader;

class AliceHelper extends AbstractHelper
{
    private $classLoader;
    private $tagLoader;
    private $locale;
    private $fixtures;
    private $dependencies;
    private $providers;
    private $processors;

    public function __construct(ClassLoader $classLoader, TagLoader $tagLoader, $locale, $fixtures, $dependencies, $providers, $processors)
    {
        $this->classLoader  = $classLoader;
        $this->tagLoader    = $tagLoader;
        $this->locale       = $locale;
        $this->fixtures     = $fixtures;
        $this->dependencies = $dependencies;
        $this->providers    = $providers;
        $this->processors   = $processors;
    }

    public function loadFilturesFiles()
    {
        if (null === $this->tagLoader->getTag('alice')) {

            return;
        }
    }

    public function loadFixtureFile($file)
    {

    }

    protected function resolveDependencies($fixture, array &$result = [])
    {
        $result[] = $fixture;
        $tree = $this->getParameter('friendly.alice.dependencies');

        if (!empty($tree[$fixture])) {
            foreach ($tree[$fixture] as $dep) {
                if (!in_array($dep, $result)) {
                    $this->resolveDeps($dep, $result);
                }
            }
        }

        return $result;
    }
}
