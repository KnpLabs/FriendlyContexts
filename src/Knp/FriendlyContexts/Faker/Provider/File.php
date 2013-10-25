<?php

namespace Knp\FriendlyContexts\Faker\Provider;

use Faker\Provider\File as Base;
use Knp\FriendlyContexts\Dictionary\Providable;

class File extends Base
{
    use Providable;

    public function getName()
    {
        return 'File';
    }
}
