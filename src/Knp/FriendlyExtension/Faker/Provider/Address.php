<?php

namespace Knp\FriendlyExtension\Faker\Provider;

use Faker\Provider\Address as FakerAddress;
use Faker\Provider\Base as FakerBase;
use Knp\FriendlyExtension\Faker\Provider\Base;

class Address extends Base
{
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

    public function supportsParent(FakerBase $parent)
    {
        return $parent instanceOf FakerAddress;
    }
}
