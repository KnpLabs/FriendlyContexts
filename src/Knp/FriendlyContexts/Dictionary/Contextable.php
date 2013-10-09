<?php

namespace Knp\FriendlyContexts\Dictionary;

use Knp\FriendlyContexts\Container;

trait Contextable
{
    use Containable;

    protected function assertArrayEquals($expected, $real)
    {
        $this->assertEquals(
            $expected,
            $real,
            "The given array\r\n\r\n" . $this->var_dump($real) . "\r\nis not equals to expected\r\n\r\n" . $this->var_dump($expected)
        );
    }

    protected function assertEquals($expected, $real, $message = "Failing to assert equals.")
    {
        if ($expected === $real) {
            return;
        }

        throw new \Exception($message, 1);
    }

    protected function var_dump($value)
    {
        if (!is_array($value)) {
            return (string) $value;;
        } else {
            $maxsize = 0;
            foreach ($value as $row) {
                if (is_array($row)) {
                    foreach ($row as $cell) {
                        $maxsize = strlen((string)$cell) > $maxsize ? strlen((string)$cell) : $maxsize;
                    }
                } else {
                    $maxsize = strlen((string)$row) > $maxsize ? strlen((string)$row) : $maxsize;
                }
            }
            $result = "";
            foreach ($value as $row) {
                $result = $result."|";
                if (is_array($row)) {
                    foreach ($row as $cell) {
                        $cell = (string)$cell;
                        while(strlen($cell) < $maxsize) { $cell = $cell." "; }
                        $result = $result." ".$cell." |";
                    }
                } else {
                    $cell = (string)$row;
                    while(strlen($cell) < $maxsize) { $cell = $cell." "; }
                    $result = $result." ".$cell." |";
                }
                $result = $result."\r\n";
            }

            return $result;
        }
    }
}
