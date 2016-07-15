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
        $maxNumOfDecimals = $this->determineMaxNumOfDecimals($mapping);
        $min = 0;
        $max = $this->determineMaxValue($mapping);

        return current($this->fakers)->fake('randomFloat', [$maxNumOfDecimals, $min, $max]);
    }

    /**
     * @param array $mapping
     *
     * @return int|null
     */
    private function determineMaxNumOfDecimals(array $mapping)
    {
        if (isset($mapping['scale'])) {
            return $mapping['scale'];
        }

        if (isset($mapping['precision'])) {
            return 0;
        }

        return null;
    }

    /**
     * @param array $mapping
     *
     * @return float|null
     */
    private function determineMaxValue(array $mapping)
    {
        if (!isset($mapping['precision']) && !isset($mapping['scale'])) {
            return null;
        }

        $scale = isset($mapping['scale']) ? (int)$mapping['scale'] : 0;
        $precision = isset($mapping['precision']) ? (int)$mapping['precision'] : 0;
        if ($precision < $scale) {
            $precision = $scale;
        }

        $maxValueStr = (int)str_repeat('9', $precision);
        $integerPartLength = $precision - $scale;

        $fractionalPart = substr($maxValueStr, 0, $integerPartLength);
        $integerPart = substr($maxValueStr, $integerPartLength);
        $maxValue = (float)"$fractionalPart.$integerPart";

        return $maxValue;
    }

    public function getName()
    {
        return 'decimal';
    }
}
