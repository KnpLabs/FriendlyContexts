<?php

namespace Knp\FriendlyContexts\Faker\Provider;

use Faker\Provider\Uuid as Base;
use Knp\FriendlyContexts\Dictionary\Providable;

class Uuid extends Base
{
    use Providable;

    public function getName()
    {
        return 'Uuid';
    }
}
