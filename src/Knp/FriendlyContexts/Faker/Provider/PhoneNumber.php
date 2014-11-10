<?php

namespace Knp\FriendlyContexts\Faker\Provider;

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

    public function supportsParent($parent)
    {
        return $parent instanceOf \Faker\Provider\PhoneNumber;
    }
}
