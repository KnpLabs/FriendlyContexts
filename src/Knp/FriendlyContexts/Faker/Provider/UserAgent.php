<?php

namespace Knp\FriendlyContexts\Faker\Provider;

class UserAgent extends Base
{
    public function supportsParent($parent)
    {
        return $parent instanceOf \Faker\Provider\UserAgent;
    }
}
