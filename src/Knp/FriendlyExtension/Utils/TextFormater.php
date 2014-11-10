<?php

namespace Knp\FriendlyExtension\Utils;

use Doctrine\Common\Util\Inflector;

class TextFormater
{
    public function toCamelCase($str)
    {
        return Inflector::camelize($str);
    }

    public function toUnderscoreCase($str)
    {
        return Inflector::tableize(
            Inflector::classify($str)
        );
    }

    public function toSpaceCase($str)
    {
        return preg_replace("/([^a-zA-Z])/", ' ', Inflector::tableize($str));
    }

    public function tableToString(array $array)
    {
        if (1 === $this->getDimentions($array)) {

            return sprintf('|%s|', implode('|', array_map(function ($e) { return sprintf(' %s ', trim($e)); }, $array)));
        }

        $sizes = array();
        foreach ($array as $row) {
            foreach ($row as $index => $cell) {
                if (empty($sizes[$index])) {
                    $sizes[$index] = 0;
                }
                $sizes[$index] = max(array($sizes[$index], mb_strlen(trim($cell), 'UTF-8')));
            }
        }

        $lines = array();
        foreach ($array as $row) {
            $cells = array();
            foreach ($row as $index => $cell) {
                $cells[] = sprintf(' %s ', $this->mbStrPad(trim($cell), $sizes[$index]));
            }
            $lines[] = sprintf('|%s|', implode('|', $cells));
        }

        return implode("\n", $lines). "\n";
    }

    public function listToArray($list, $delimiters = [', ', ' and '], $parser = "#||#")
    {
        $list  = str_replace('"', '', $list);

        foreach ($delimiters as $delimiter) {
            $list  = str_replace($delimiter, $parser, $list);
        }

        if (!is_string($list)) {
            throw new \Exception($this->var_dump($list));
        }

        $parts = explode($parser, $list);

        $parts = array_map('trim', $parts);
        $parts = array_filter($parts, 'strlen');

        return $parts;
    }

    protected function getDimentions(array $array)
    {
        return $this->goDeeper($array, 0);
    }

    protected function goDeeper(array $array, $deep)
    {
        $deep++;
        foreach ($array as $elem) {
            if (is_array($elem)) {
                $deep = max([ $this->goDeeper($elem, $deep), $deep ]);
            }
        }

        return $deep;
    }

    protected function mbStrPad($input, $pad_length, $pad_string = ' ', $pad_type = STR_PAD_RIGHT)
    {
        $diff = strlen($input) - mb_strlen($input, 'UTF8');

        return str_pad($input, $pad_length + $diff, $pad_string, $pad_type);
    }
}
