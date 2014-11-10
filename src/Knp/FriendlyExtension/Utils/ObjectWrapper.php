<?php

namespace Knp\FriendlyExtension\Utils;

final class ObjectWrapper
{
    private $wrapped;

    public function setWrappedObject($wrapped)
    {
        $this->wrapped = $wrapped;
    }
}
