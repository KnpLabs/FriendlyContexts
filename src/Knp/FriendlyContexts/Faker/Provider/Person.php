<?php

namespace Knp\FriendlyContexts\Faker\Provider;

class Person extends Base
{
    public function fullname()
    {
        return $this->parent->name();
    }

    public function supportsParent($parent)
    {
        return $parent instanceOf \Faker\Provider\Person;
    }
}
