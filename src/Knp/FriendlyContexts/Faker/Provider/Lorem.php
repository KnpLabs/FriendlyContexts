<?php

namespace Knp\FriendlyContexts\Faker\Provider;

use Faker\Provider\Lorem as Base;
use Knp\FriendlyContexts\Dictionary\Providable;

class Lorem extends Base
{
    use Providable;

    public function getName()
    {
        return 'Lorem';
    }
}
