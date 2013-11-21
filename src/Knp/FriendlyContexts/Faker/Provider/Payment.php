<?php

namespace Knp\FriendlyContexts\Faker\Provider;

class Payment extends Base
{
    public function supportsParent($parent)
    {
        return $parent instanceOf \Faker\Provider\Payment;
    }
}
