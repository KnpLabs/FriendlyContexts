<?php

namespace Knp\FriendlyExtension\Context\Helper;

use Knp\FriendlyExtension\Context\Helper\HelperInterface;
use Knp\FriendlyExtension\Utils\TextFormater;

class AsserterHelper implements HelperInterface
{
    protected $formater;

    public function __construct(TextFormater $formater)
    {
        $this->formater = $formater;
    }

    public function getName()
    {
        return 'asserter';
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
        $message = $message ?: sprintf("The given array\r\n\r\n%s\r\ndoes not contains the following rows\r\n\r\n%s", $this->explode($real), $this->explode($expected));

        foreach ($expected as $key => $value) {
            $this->assert(isset($real[$key]), $message);

            if (is_array($value)) {
                $this->assert(is_array($real[$key]), $message);
                $this->assertArrayContains($value, $real[$key], $message);

                continue;
            }

            $value      = is_string($value) ? trim($value) : $value;
            $real[$key] = is_string($real[$key]) ? trim($real[$key]) : $real[$key];

            $this->assert($value === $real[$key], $message);
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
