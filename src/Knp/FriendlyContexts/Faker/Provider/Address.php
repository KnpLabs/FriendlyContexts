<?php

namespace Knp\FriendlyContexts\Faker\Provider;

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

    public function supportsParent($parent)
    {
        return $parent instanceOf \Faker\Provider\Address;
    }
}
