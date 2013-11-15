<?php

namespace Knp\FriendlyContexts\Faker\Provider;

use Faker\Provider\Person as Base;
use Knp\FriendlyContexts\Dictionary\Providable;

class Person extends Base
{
    use Providable;

    public function fullname()
    {
        return $this->parent->name();
    }

    public function getName()
    {
        return 'Person';
    }
}
