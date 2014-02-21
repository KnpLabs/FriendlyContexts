<?php

namespace Knp\FriendlyContexts\Context;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Doctrine\Common\Inflector\Inflector;
use Behat\Mink\Element\DocumentElement;

class RawPageContext extends RawMinkContext
{
    private $pages = [];

    public function getPage($page)
    {
        if (isset($this->pages[$page])) {
            return $this->pages[$page];
        }

        $class = sprintf(
            '%s\\%sPage',
            $this->config['page']['namespace'],
            ucfirst(Inflector::camelize(str_replace(' ', '_', $page)))
        );

        $this->pages[$page] = $this->get('friendly.page.resolver')->create(
            $this->getSession(),
            $class
        );

        return $this->pages[$page];
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
}
