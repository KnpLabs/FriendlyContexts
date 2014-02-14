<?php

namespace Knp\FriendlyContexts\Utils;

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

    protected function explode($value)
    {
        if (!is_array($value)) {
            return (string) $value;;
        } else {
            return $this->formater->tableToString($value);
        }
    }
}
