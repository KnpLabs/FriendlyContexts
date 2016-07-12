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
        $max = $this->determineMaxValue($mapping);

        return current($this->fakers)->fake('numberBetween', [$min, $max]);
    }

    /**
     * @param array $mapping
     *
     * @return int
     */
    private function determineMaxValue(array $mapping)
    {
        $defaultMax = 2000000000;

        if (!isset($mapping['length']) || $mapping['length'] < 1) {
            return $defaultMax;
        }

        $maxValue = (int)str_repeat('9', $mapping['length']);
        if ($maxValue > $defaultMax) {
            $maxValue = $defaultMax;
        }

        return $maxValue;
    }

    public function getName()
    {
        return 'int';
    }
}
