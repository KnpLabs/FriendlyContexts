<?php

namespace Knp\FriendlyContexts\Tool;

class Asserter
{
    public function assertArrayEquals($expected, $real)
    {
        $message = sprintf("The given array\r\n\r\n%s\r\nis not equals to expected\r\n\r\n%s", $this->explode($real), $this->explode($expected));

        $this->assertEquals(
            $expected,
            $real,
            $message
        );
    }

    public function assertEquals($expected, $real, $message = "Failing to assert equals.")
    {
        if ($expected === $real) {
            return true;
        }

        throw new \Exception($message, 1);
    }

    protected function explode($value)
    {
        if (!is_array($value)) {
            return (string) $value;;
        } else {
            return $this->buildStringFromArray($value);
        }
    }

    protected function getMaxElementSize(array $array = [])
    {
        $maxsize = 0;
        foreach ($array as $row) {
            if (is_array($row)) {
                foreach ($row as $cell) {
                    $maxsize = strlen((string)$cell) > $maxsize ? strlen((string)$cell) : $maxsize;
                }
            } else {
                $maxsize = strlen((string)$row) > $maxsize ? strlen((string)$row) : $maxsize;
            }
        }

        return $maxsize;
    }

    protected function buildStringFromArray(array $array = [])
    {
        $maxsize = $this->getMaxElementSize($array);
        $result = "";
        foreach ($array as $row) {
            $result = $result."|";
            if (is_array($row)) {
                foreach ($row as $cell) {
                    $cell = (string)$cell;
                    while(strlen($cell) < $maxsize) { $cell = $cell." "; }
                    $result = sprintf('%s %s |', $result, $this->explode($cell));
                }
            } else {
                $cell = (string)$row;
                while(strlen($cell) < $maxsize) { $cell = $cell." "; }
                $result = sprintf('%s %s |', $result, $this->explode($cell));
            }
            $result = $result."\r\n";
        }

        return $result;
    }
}
