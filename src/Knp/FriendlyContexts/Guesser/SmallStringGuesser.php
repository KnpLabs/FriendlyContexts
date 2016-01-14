<?php

namespace Knp\FriendlyContexts\Guesser;

class SmallStringGuesser extends AbstractGuesser implements GuesserInterface
{
    /**
     * @param array $mapping
     *
     * @return boolean
     */
    public function supports(array $mapping)
    {
        $mapping = array_merge(['type' => null, 'length' => null], $mapping);

        return in_array($mapping['type'], ['string', 'text']) && $mapping['length'] !== null;
    }

    /**
     * @param string     $str
     * @param array|null $mapping
     *
     * @return string
     */
    public function transform($str, array $mapping = null)
    {
        return $str;
    }

    /**
     * @param array $mapping
     *
     * @return string
     */
    public function fake(array $mapping)
    {
        return current($this->fakers)->fake('lexify', [str_repeat('?', $mapping['length'])]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'smallstring';
    }
}
