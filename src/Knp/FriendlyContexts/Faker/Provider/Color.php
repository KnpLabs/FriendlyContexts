<?php

namespace Knp\FriendlyContexts\Faker\Provider;

use Faker\Provider\Color as Base;
use Knp\FriendlyContexts\Dictionary\Providable;

class Color extends Base
{
    use Providable;

    public function getName()
    {
        return 'Color';
    }
}
