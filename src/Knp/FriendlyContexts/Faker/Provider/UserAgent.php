<?php

namespace Knp\FriendlyContexts\Faker\Provider;

use Faker\Provider\UserAgent as Base;
use Knp\FriendlyContexts\Dictionary\Providable;

class UserAgent extends Base
{
    use Providable;

    public function getName()
    {
        return 'UserAgent';
    }
}
