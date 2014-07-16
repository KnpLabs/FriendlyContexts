<?php

namespace Knp\FriendlyExtension\Faker\Provider;

use Faker\Provider\Base as FakerBase;
use Faker\Provider\Person as FakerPerson;
use Knp\FriendlyExtension\Faker\Provider\Base;

class Person extends Base
{
    public function fullname()
    {
        return $this->parent->name();
    }

    public function supportsParent(FakerBase $parent)
    {
        return $parent instanceOf FakerPerson;
    }
}
