<?php

namespace Knp\FriendlyContexts\Guesser;

class BooleanGuesser extends AbstractGuesser implements GuesserInterface
{
    public function supports(array $mapping)
    {
        $mapping = array_merge([ 'type' => null ], $mapping);

        return $mapping['type'] === 'boolean';
    }

    public function transform($str, array $mapping = null)
    {
        $str = strtolower($str);

        $formats = [
            'active'    => true,
            'activated' => true,
            'enabled'   => true,
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

    public function fake(array $mapping)
    {
        return true;
    }

    public function getName()
    {
        return 'boolean';
    }
}
