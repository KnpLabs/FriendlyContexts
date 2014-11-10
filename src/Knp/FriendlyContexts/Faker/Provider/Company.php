<?php

namespace Knp\FriendlyContexts\Faker\Provider;

class Company extends Base
{
    public function supportsParent($parent)
    {
        return $parent instanceOf \Faker\Provider\Company;
    }
}
