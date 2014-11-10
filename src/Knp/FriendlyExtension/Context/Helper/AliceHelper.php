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

    public function __construct(ClassLoader $classLoader, TagLoader $tagLoader, $locale, array $fixtures, array $dependencies, array $providers, array $processors)
    {
        $this->classLoader  = $classLoader;
        $this->tagLoader    = $tagLoader;
        $this->locale       = $locale;
        $this->fixtures     = $fixtures;
        $this->dependencies = $dependencies;
        $this->providers    = $this->classLoader->instanciateAll($providers);
        $this->processors   = $this->classLoader->instanciateAll($processors);
    }

    public function getName()
    {
        return 'alice';
    }

    public function loadFiles()
    {
        if (null === $tag = $this->tagLoader->getTag('alice')) {

            return;
        }

        if (in_array('*', $tag->getArguments())) {
            $files = array_keys($this->fixtures);
        } else {
            $files = $this->resolveDepsFromArray($tag->getArguments());
        }

        foreach ($this->fixtures as $name => $fixture) {
            $this->exists($name, true);
        }

        foreach ($this->fixtures as $name => $fixture) {
            if (in_array($name, $files)) {
                $this->load($name);
            }
        }
    }

    public function load($name)
    {
        $file = $this->getFileFromName($name);

        $objects = $this->loader->load($file);

        foreach ($this->processors as $proc) {
            foreach ($objects as $obj) {
                $proc->preProcess($obj);
            }
        }

        foreach ($objects as $obj) {
            $this->get('doctrine')->persist($obj);
        }

        $this->get('doctrine')->flush();

        foreach ($this->processors as $proc) {
            foreach ($objects as $obj) {
                $proc->postProcess($obj);
            }
        }
    }

    public function exists($name, $throw = false)
    {
        if (array_key_exists($name, $this->fixtures)) {

            return true;
        }

        if (is_file($name)) {

            return true;
        }

        if (false === $throw) {

            return false;
        }

        throw new \Exception(sprintf(
            'File or fixture "%s" unknown. "%s" expected',
            $name,
            implode('", "', array_keys($this->fixtures)))
        );
    }

    public function getFileFromName($name)
    {
        $this->exists($name, true);

        if (is_file($name)) {

            return $name;
        }

        return $this->fixtures[$name];
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
