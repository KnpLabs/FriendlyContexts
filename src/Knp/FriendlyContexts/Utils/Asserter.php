<?php

namespace Knp\FriendlyContexts\Utils;

class Asserter
{
    protected $formater;

    public function __construct(TextFormater $formater)
    {
        $this->formater = $formater;
    }

    public function assertArrayEquals($expected, $real, $fullText = false)
    {
        $message = sprintf("The given array\r\n\r\n%s\r\nis not equals to expected\r\n\r\n%s", $this->explode($real), $this->explode($expected));

        if (false === $fullText) {
            $this->assertEquals(
                $expected,
                $real,
                $message
            );
        } else {
            $this->assertEquals(
                $this->explode($expected),
                $this->explode($real),
                $message
            );
        }
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
            return $this->formater->tableToString($value);
        }
    }
}
