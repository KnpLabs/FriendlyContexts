<?php

namespace Knp\FriendlyContexts\Guesser;

class DecimalGuesser extends AbstractGuesser implements GuesserInterface
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
