<?php

namespace Knp\FriendlyExtension\Faker\Provider;

use Faker\Provider\Base as FakerBase;
use Faker\Provider\DateTime as FakerDateTime;
use Knp\FriendlyExtension\Faker\Provider\Base;

class DateTime extends Base
{
    public function timestamp()
    {
        return $this->parent->unixTime();
    }

    public function supportsParent(FakerBase $parent)
    {
        return $parent instanceOf FakerDateTime;
    }
}
