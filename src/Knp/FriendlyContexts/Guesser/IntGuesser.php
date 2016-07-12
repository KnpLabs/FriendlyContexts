<?php

namespace Knp\FriendlyContexts\Guesser;

class IntGuesser extends AbstractGuesser implements GuesserInterface
{
    public function supports(array $mapping)
    {
        $mapping = array_merge([ 'type' => null ], $mapping);

        return $mapping['type'] === 'integer';
    }

    public function transform($str, array $mapping = null)
    {
        return (int) round($str);
    }

    public function fake(array $mapping)
    {
        $min = 0;
        $max = 2000000000;

        if (isset($mapping['length']) && $mapping['length'] > 0) {
            $max = (int)str_repeat('9', $mapping['length']);
        }

        return current($this->fakers)->fake('numberBetween', [$min, $max]);
    }

    public function getName()
    {
        return 'int';
    }
}
