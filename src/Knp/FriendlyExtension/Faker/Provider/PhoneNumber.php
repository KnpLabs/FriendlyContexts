<?php

namespace Knp\FriendlyExtension\Faker\Provider;

use Faker\Provider\Base as FakerBase;
use Faker\Provider\PhoneNumber as FakerPhoneNumber;
use Knp\FriendlyExtension\Faker\Provider\Base;

class PhoneNumber extends Base
{
    public function phone()
    {
        return $this->parent->phoneNumber();
    }

    public function fax()
    {
        return $this->parent->phoneNumber();
    }

    public function mobile()
    {
        return $this->parent->phoneNumber();
    }

    public function supportsParent(FakerBase $parent)
    {
        return $parent instanceOf FakerPhoneNumber;
    }
}
