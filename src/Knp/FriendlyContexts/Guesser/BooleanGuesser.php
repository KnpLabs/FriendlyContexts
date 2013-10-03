<?php

namespace Knp\FriendlyContexts\Guesser;

class BooleanGuesser implements GuesserInterface
{
    public function supports($mapping)
    {
        $mapping = array_merge([ 'type' => null ], $mapping);

        return $mapping['type'] === 'boolean';
    }

    public function transform($str)
    {
        $str = strtolower($str);

        $formats = [
            'active'    => true,
            'activated' => true,
            'disabled'  => false,
            'true'      => true,
            'false'     => false,
            'yes'       => true,
            'no'        => false,
            '1'         => true,
            '0'         => false,
        ];

        if (false === array_key_exists($str, $formats)) {
            throw new \Exception(
                sprintf(
                    '"%s" is not a supported format. Supported format : [%s].',
                    $str,
                    implode(', ', array_keys($formats))
                )
            );
        }

        return $formats[$str];
    }
}
