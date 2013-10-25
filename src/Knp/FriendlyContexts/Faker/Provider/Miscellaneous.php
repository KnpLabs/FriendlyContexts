<?php

namespace Knp\FriendlyContexts\Faker\Provider;

use Faker\Provider\Miscellaneous as Base;
use Knp\FriendlyContexts\Dictionary\Providable;

class Miscellaneous extends Base
{
    use Providable;

    public function getName()
    {
        return 'Miscellaneous';
    }
}
