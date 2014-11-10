<?php

namespace Knp\FriendlyContexts\Context;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Knp\FriendlyContexts\Page\Page;
use Behat\Gherkin\Node\TableNode;

class RawPageContext extends RawMinkContext
{
    private $pages = [];

    public function visitPage($page, $arguments = null)
    {
        $this->getSession()->visit($this->locatePath($this->getPagePath($page, $arguments)));
    }

    public function assertPage($page, $arguments = null)
    {
        try {
            $this->assertSession()->addressEquals($this->getPagePath($page, $arguments));
        } catch (\Exception $e) {
            $this->assertSession()->addressEquals(sprintf(
                '%s/',
                $this->getPagePath($page, $arguments)
            ));
        }
    }

    public function getPage($page)
    {
        if (isset($this->pages[$page])) {
            return $this->pages[$page];
        }

        $class = $this->getPageClassResolver()->resolveName($page);

        $this->pages[$page] = $this->getPageClassResolver()->create(
            $this->getSession(),
            $class
        );

        return $this->pages[$page];
    }

    public function getPagePath($page, $arguments = null)
    {
        list($parameters, $entities) = $this->extractTable($arguments);

        $page = $this->getPage($page);

        return $this->resolvePagePath($page, $parameters, $entities);
    }

    protected function getEntityFromRecordBag($entity, $field)
    {
        $class = $this->resolveEntity($entity)->getName();

        $record = $this
            ->getRecordBag()
            ->getCollection($class)
            ->search($field)
        ;

        if (null === $record) {
            throw new \Exception(sprintf(
                'No entity %s has been found for  "%s"',
                $class,
                $field
            ));
        }

        return $record->getEntity();
    }

    protected function resolvePagePath(Page $page, $parameters, $entities)
    {
        $path = $page->getPath();

        foreach ($parameters as $key => $value) {
            $path = str_replace(sprintf('{%s}', $key), $value, $path);
        }

        if (!preg_match_all('/\{([a-zA-Z0-9]+\.[a-zA-Z0-9]+)\}/', $path, $matches)) {
            return $path;
        }

        $properties = array();

        foreach ($matches[1] as $parameter) {
            list($entityName, $field) = explode('.', $parameter);

            if (!isset($entities[$entityName])) {
                throw new \Exception(sprintf(
                    'No entity can be resolved for "%s"',
                    $entityName
                ));
            }

            $entity = $entities[$entityName];
            $properties[] = PropertyAccess::createPropertyAccessor()
                ->getValue($entity, $field)
            ;
        }

        foreach ($matches[0] as $index => $pattern) {
            $path = str_replace($pattern, $properties[$index], $path);
        }

        return $path;
    }

    protected function extractTable($parameters = null)
    {
        if (null === $parameters) {
            return [[], []];
        }

        if ($parameters instanceof TableNode) {
            $parameters = $parameters->getRowsHash();
        }

        if (!is_array($parameters)) {
            throw new \InvalidArgumentException(
                'You must precised a valid array or Behat\Gherkin\Node\TableNode to extract'
            );
        }

        $entities = [];

        foreach ($parameters as $name => $value) {
            $matches = array();
            if (preg_match('/^the (.+) "([^"]+)"$/', $value, $matches)) {
                $entity = $this->getEntityFromRecordBag($matches[1], $matches[2]);

                $entities[$name] = $entity;
                unset($parameters[$name]);
            }
        }

        return array($parameters, $entities);
    }
}
