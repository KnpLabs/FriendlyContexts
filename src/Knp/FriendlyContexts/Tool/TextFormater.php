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
}
