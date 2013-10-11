<?php

namespace Knp\FriendlyContexts\Faker\Provider;

use Faker\Provider\Payment as Base;
use Knp\FriendlyContexts\Dictionary\Providable;

class Payment extends Base
{
    use Providable;

    public function getName()
    {
        return 'Payment';
    }
}
