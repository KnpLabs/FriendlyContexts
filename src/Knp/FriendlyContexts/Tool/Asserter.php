<?php

namespace Knp\FriendlyContexts\Tool;

class Asserter
{
    protected $formater;

    public function __construct(TextFormater $formater)
    {
        $this->formater = $formater;
    }

    public function assertArrayEquals($expected, $real)
    {
        $message = sprintf("The given array\r\n\r\n%s\r\nis not equals to expected\r\n\r\n%s", $this->explode($real), $this->explode($expected));
        var_dump($message);

        var_dump($expected, $real);

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

    protected function getMaxElementSize(array $array = [], $maxsize = 0)
    {
        foreach ($array as $row) {
            if (is_array($row)) {
                $maxsize = $this->getMaxElementSize($row, $maxsize);
            } else {
                $maxsize = strlen((string) $row) > $maxsize ? strlen((string) $row) : $maxsize;
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
                    $result = sprintf(
                        '%s %s |',
                        $result,
                        $this->explode($this->formater->addSpaceAfter((string) $cell, $maxsize))
                    );
                }
            } else {
                $result = sprintf(
                    '%s %s |',
                    $result,
                    $this->explode($this->formater->addSpaceAfter((string) $row, $maxsize))
                );
            }
            $result = $result."\r\n";
        }

        return $result;
    }
}
