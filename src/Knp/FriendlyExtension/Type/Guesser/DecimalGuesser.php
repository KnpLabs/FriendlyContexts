<?php

namespace Knp\FriendlyExtension\Type\Guesser;

use Knp\FriendlyExtension\Type\Guesser\AbstractGuesser;

final class DecimalGuesser extends AbstractGuesser
{
    public function supports(array $mapping)
    {
        $mapping = array_merge([ 'type' => null ], $mapping);

        return in_array($mapping['type'], [ 'decimal', 'float' ]);
    }

    public function transform($str, array $mapping = null)
    {
        return (float) $str;
    }

    public function fake(array $mapping)
    {
        return current($this->fakers)->fake('randomFloat');
    }

    public function getName()
    {
        return 'decimal';
    }
}
