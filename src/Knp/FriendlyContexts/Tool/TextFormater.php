<?php

namespace Knp\FriendlyContexts\Tool;

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
