<?php

namespace Knp\FriendlyContexts\Faker\Provider;

use Faker\Provider\Address as Base;
use Knp\FriendlyContexts\Dictionary\Providable;

class Address extends Base
{
    use Providable;

    public function getName()
    {
        return 'Address';
    }
}
