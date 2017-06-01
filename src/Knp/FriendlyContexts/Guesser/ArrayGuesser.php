<?php

namespace Knp\FriendlyContexts\Guesser;


use Doctrine\DBAL\Types\Type;

class ArrayGuesser extends AbstractGuesser implements GuesserInterface
{
    public function supports(array $mapping)
    {
        $mapping = array_merge([ 'type' => null ], $mapping);

        // TODO: support other Doctrine array types
        return $mapping['type'] === Type::TARRAY;
    }

    public function transform($str, array $mapping)
    {
        return json_decode($str);
    }

    public function fake(array $mapping)
    {
        return ['foo' => 'bar'];
    }

    public function getName()
    {
        return 'array';
    }

}
