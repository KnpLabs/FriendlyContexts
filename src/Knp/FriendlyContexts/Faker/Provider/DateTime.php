<?php

namespace Knp\FriendlyContexts\Faker\Provider;

use Faker\Provider\DateTime as Base;
use Knp\FriendlyContexts\Dictionary\Providable;

class DateTime extends Base
{
    use Providable;

    public function timestamp()
    {
        return Base::unixTime();
    }

    public function getName()
    {
        return 'DateTime';
    }
}
