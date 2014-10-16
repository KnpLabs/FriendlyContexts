<?php

namespace Knp\FriendlyExtension\Context\Helper;

use Behat\Gherkin\Node\TableNode;
use Knp\FriendlyExtension\Context\Helper\AbstractHelper;
use Knp\FriendlyExtension\Page\Page;
use Knp\FriendlyExtension\Page\Resolver\PageClassResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;

class PageHelper extends AbstractHelper
{
    private $pageClassResolver;
    private $pages = [];

    public function __construct(PageClassResolver $pageClassResolver)
    {
        $this->pageClassResolver = $pageClassResolver;
    }

    public function getName()
    {
        return 'page';
    }

    public function getPage($page)
    {
        if (isset($this->pages[$page])) {
            return $this->pages[$page];
        }

        $class = $this->pageClassResolver->resolveName($page);

        $this->pages[$page] = $this->pageClassResolver->create(
            $this->get('mink')->getSession(),
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

    private function resolvePagePath(Page $page, $parameters, $entities)
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

    private function extractTable($parameters = null)
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
                if (null === $entity = $this->get('record')->find($matches[1], $matches[2])) {
                    throw new \Exception(sprintf(
                        'No entity %s has been found for  "%s"',
                        $class,
                        $field
                    ));
                }

                $entities[$name] = $entity;
                unset($parameters[$name]);
            }
        }

        return array($parameters, $entities);
    }
}
