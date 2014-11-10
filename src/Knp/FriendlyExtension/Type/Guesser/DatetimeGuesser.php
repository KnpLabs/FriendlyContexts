<?php

namespace Knp\FriendlyExtension\Type\Guesser;

use Knp\FriendlyExtension\Type\Guesser\AbstractGuesser;

final class DatetimeGuesser extends AbstractGuesser
{
    public function supports(array $mapping)
    {
        $mapping = array_merge([ 'type' => null ], $mapping);

        return in_array($mapping['type'], ['datetime', 'date', 'time']);
    }

    public function transform($str, array $mapping = null)
    {
        $time = strtotime($str);

        if (false === $time) {
            throw new \Exception(sprintf('"%s" is not a supported date/time/datetime format. To know which formats are supported, please visit http://www.php.net/manual/en/datetime.formats.php', $str));
        }

        return \DateTime::createFromFormat('U', $time);
    }

    public function fake(array $mapping)
    {
        return new \DateTime();
    }

    public function getName()
    {
        return 'datetime';
    }
}
