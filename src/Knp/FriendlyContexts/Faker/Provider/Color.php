<?php

namespace Knp\FriendlyContexts\Faker\Provider;

class Color extends Base
{
    public function supportsParent($parent)
    {
        return $parent instanceOf \Faker\Provider\Color;
    }
}
