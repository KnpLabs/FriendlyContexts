<?php

namespace Knp\FriendlyContexts\Faker\Provider;

use Faker\Provider\Internet as Base;
use Knp\FriendlyContexts\Dictionary\Providable;

class Internet extends Base
{
    use Providable;

    public function login()
    {
        return $this->parent->userName();
    }

    public function getName()
    {
        return 'Internet';
    }
}
