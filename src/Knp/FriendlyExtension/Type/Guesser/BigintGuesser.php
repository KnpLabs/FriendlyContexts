<?php

namespace Knp\FriendlyExtension\Type\Guesser;

use Knp\FriendlyExtension\Type\Guesser\AbstractGuesser;
use Knp\FriendlyExtension\Type\Guesser\IntGuesser;

final class BigintGuesser extends AbstractGuesser
{
    private $intGuesser;

    public function __construct(IntGuesser $intGuesser)
    {
        $this->intGuesser = $intGuesser;
    }

    public function supports(array $mapping)
    {
        $mapping = array_merge([ 'type' => null ], $mapping);

        return $mapping['type'] === 'bigint';
    }

    public function transform($str, array $mapping = null)
    {
        return $this->intGuesser->transform($str, $mapping);
    }


    public function fake(array $mapping)
    {
        return current($this->fakers)->fake('randomNumber');
    }

    public function getName()
    {
        return 'bigint';
    }
}
