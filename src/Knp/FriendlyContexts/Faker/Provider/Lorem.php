<?php

namespace Knp\FriendlyContexts\Faker\Provider;

class Lorem extends Base
{
    public function supportsParent($parent)
    {
        return $parent instanceOf \Faker\Provider\Lorem;
    }
}
