<?php

namespace Knp\FriendlyContexts\Faker\Provider;

class File extends Base
{
    public function supportsParent($parent)
    {
        return $parent instanceOf \Faker\Provider\File;
    }
}
