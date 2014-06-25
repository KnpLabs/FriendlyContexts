<?php

namespace Knp\FriendlyExtension\Faker\Provider;

use Faker\Provider\Base as FakerBase;
use Faker\Provider\Internet as FakerInternet;
use Knp\FriendlyExtension\Faker\Provider\Base;

class Internet extends Base
{
    public function login()
    {
        return $this->parent->userName();
    }


    public function supportsParent(FakerBase $parent)
    {
        return $parent instanceOf FakerInternet;
    }
}
