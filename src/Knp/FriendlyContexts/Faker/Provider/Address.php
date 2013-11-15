<?php

namespace Knp\FriendlyContexts\Faker\Provider;

use Faker\Provider\Address as Base;
use Knp\FriendlyContexts\Dictionary\Providable;

class Address extends Base
{
    use Providable;

    public function address1()
    {
        return $this->parent->streetAddress();
    }

    public function postalcode()
    {
        return $this->parent->postcode();
    }

    public function zipcode()
    {
        return $this->parent->postcode();
    }

    public function getName()
    {
        return 'Address';
    }
}
