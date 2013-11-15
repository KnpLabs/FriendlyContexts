<?php

namespace Knp\FriendlyContexts\Faker\Provider;

use Faker\Provider\PhoneNumber as Base;
use Knp\FriendlyContexts\Dictionary\Providable;

class PhoneNumber extends Base
{
    use Providable;

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

    public function getName()
    {
        return 'PhoneNumber';
    }
}
