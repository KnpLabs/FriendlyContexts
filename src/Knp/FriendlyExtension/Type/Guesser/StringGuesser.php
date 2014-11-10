<?php

namespace Knp\FriendlyExtension\Type\Guesser;

use Knp\FriendlyExtension\Type\Guesser\AbstractGuesser;

final class StringGuesser extends AbstractGuesser
{
    public function supports(array $mapping)
    {
        $mapping = array_merge([ 'type' => null ], $mapping);

        return in_array($mapping['type'], ['string', 'text']);
    }

    public function transform($str, array $mapping = null)
    {
        return $str;
    }

    public function fake(array $mapping)
    {
        $mapping = array_merge(
            ['fieldName' => null, 'type' => null],
            $mapping
        );

        $name = $mapping['fieldName'];

        foreach ($this->fakers as $faker) {
            if ($faker->isFakable($name)) {
                return $faker->fake($name);
            }
        }

        return $this->fake(['fieldName' => 'text' === $mapping['type'] ? 'paragraph' : 'word']);
    }

    public function getName()
    {
        return 'string';
    }
}
