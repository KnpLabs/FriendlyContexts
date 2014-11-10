<?php

namespace Knp\FriendlyContexts\Faker\Provider;

class DateTime extends Base
{
    public function timestamp()
    {
        return $this->parent->unixTime();
    }

    public function supportsParent($parent)
    {
        return $parent instanceOf \Faker\Provider\DateTime;
    }
}
