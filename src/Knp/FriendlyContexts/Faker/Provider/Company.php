<?php

namespace Knp\FriendlyContexts\Faker\Provider;

use Faker\Provider\Company as Base;
use Knp\FriendlyContexts\Dictionary\Providable;

class Company extends Base
{
    use Providable;

    public function getName()
    {
        return 'Company';
    }
}
