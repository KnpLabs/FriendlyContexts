<?php

namespace Knp\FriendlyExtension\Context\Helper;

use Knp\FriendlyExtension\Context\Helper\AbstractHelper;
use Knp\FriendlyExtension\Record\Collection\Bag;
use Symfony\Component\PropertyAccess\PropertyAccess;

class RecordHelper extends AbstractHelper
{
    private $bag;

    public function __construct(Bag $bag)
    {
        $this->bag = $bag;
    }

    public function getName()
    {
        return 'record';
    }

    public function clear()
    {
        $this->bag->clear();
    }

    public function getCollection($class)
    {
        return $this->bag->getCollection($class);
    }

    public function find($class, $value = null)
    {
        $collection = $this->getCollection($class);

        if (null === $value) {

            return array_map(function ($e) { return $e->getEntity(); }, $collection->all());
        }

        return $collection->search($value);
    }

    public function attach($entity, array $values)
    {
        $collection = $this->getCollection($entity);

        $collection->attach($entity, $this->buildValues($entity, $values));
    }

    private function buildValues($entity, array $values)
    {
        $collection = $this->getCollection($entity);
        $accessor = PropertyAccess::getPropertyAccessor();

        foreach ($collection->getHeaders() as $header) {
            if (array_key_exists($header, $values)) {
                continue;
            }
            try {
                $value = $accessor->getValue($entity, $header);
                if (is_scalar($value)) {
                    $values[$header] = $value;
                }
            } catch (\Exception $ex) {
                unset($ex);
            }
        }

        return $values;
    }
}
