<?php

namespace Knp\FriendlyContexts\Faker\Provider;

class Uuid extends Base
{
    public function supportsParent($parent)
    {
        return $parent instanceOf \Faker\Provider\Uuid;
    }
}
