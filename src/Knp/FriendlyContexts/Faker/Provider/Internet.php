<?php

namespace Knp\FriendlyContexts\Faker\Provider;

class Internet extends Base
{
    public function login()
    {
        return $this->parent->userName();
    }

    public function supportsParent($parent)
    {
        return $parent instanceOf \Faker\Provider\Internet;
    }
}
