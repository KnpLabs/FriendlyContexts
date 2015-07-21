<?php

namespace Knp\FriendlyContexts\Utils;

use Knp\FriendlyContexts\Table\NodesBuilder;

class Asserter
{
    protected $formater;

    public function __construct(TextFormater $formater)
    {
        $this->formater = $formater;
    }

    public function assertArrayEquals(array $expected, array $real, $fullText = false)
    {
        $message = sprintf("The given array\r\n\r\n%s\r\nis not equals to expected\r\n\r\n%s", $this->explode($real), $this->explode($expected));

        if (false === $fullText) {
            return $this->assertEquals(
                $expected,
                $real,
                $message
            );
        } else {
            return $this->assertEquals(
                $this->explode($expected),
                $this->explode($real),
                $message
            );
        }
    }

    public function assertArrayContains(array $expected, array $real, $message = null)
    {
        $message = $message ?: sprintf("The given array\r\n\r\n%s\r\ndoes not contain the following rows\r\n\r\n%s", $this->explode($real), $this->explode($expected));
        $indexes = [];

        foreach ($expected as $row) {
            $this->assert(is_array($row), $message);
        }

        foreach ($real as $row) {
            $this->assert(is_array($row), $message);
        }

        $nodes = (new NodesBuilder)->build($real);
        $nodes = $nodes->search(current(current($expected)));

        foreach ($nodes as $initial) {
            $result    = true;
            $cells     = $expected;
            $lineStart = $initial;
            do {
                $columns       = array_shift($cells);
                $columnElement = $lineStart;
                do {
                    $content = array_shift($columns);
                    $result = $columnElement
                        ? $content === $columnElement->getContent()
                        : false
                    ;
                    $columnElement = $columnElement ? $columnElement->getRight() : null;
                } while (!empty($columns) && $result);
                $lineStart = $lineStart ? $lineStart->getBottom() : null;
            } while (!empty($cells) && $result);

            if ($result) {

                return true;
            }
        }

        $this->assert(false, $message);
    }

    public function assertEquals($expected, $real, $message = "Failing to assert equals.")
    {
        return $this->assert($expected === $real, $message);
    }

    public function assertNotEquals($expected, $real, $message = "Failing to assert not equals.")
    {
        return $this->assert($expected !== $real, $message);
    }

    public function assert($result, $message = "Assert failure")
    {
        if (false === $result) {
            throw new \Exception($message, 1);
        }

        return true;
    }

    private function explode($value)
    {
        if (!is_array($value)) {
            return (string) $value;
        } else {
            return $this->formater->tableToString($value);
        }
    }
}
