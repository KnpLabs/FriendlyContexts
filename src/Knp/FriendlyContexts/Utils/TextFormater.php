<?php

namespace Knp\FriendlyContexts\Utils;

class TextFormater
{
    public function toCamelCase($str)
    {
        return preg_replace('/ /', '', ucwords($str));
    }

    public function toUnderscoreCase($str)
    {
        $str = strtolower(preg_replace("[A-Z]", "_\$1", $str));

        return preg_replace("/([^a-zA-Z])/", '_', $str);
    }

    public function toSpaceCase($str)
    {
        $str = strtolower(preg_replace("[A-Z]", "_\$1", $str));

        return preg_replace("/([^a-zA-Z])/", ' ', $str);
    }

    public function tableToString(array $array)
    {
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
                $cells[] = sprintf(' %s ', str_pad(trim($cell), $sizes[$index]));
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

    public function addSpaceAfter($str, $limit = 0)
    {
        while (strlen($str) < $limit) {
            $str = $str." ";
        }

        return $str;
    }
}
