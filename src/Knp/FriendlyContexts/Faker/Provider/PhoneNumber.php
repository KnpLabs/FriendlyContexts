<?php

namespace Knp\FriendlyContexts\Faker\Provider;

use Faker\Provider\PhoneNumber as Base;
use Knp\FriendlyContexts\Dictionary\Providable;

class PhoneNumber extends Base
{
    use Providable;

    public function getName()
    {
        return 'PhoneNumber';
    }
}
