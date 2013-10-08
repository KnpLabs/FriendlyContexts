<?php

namespace Knp\FriendlyContexts\Guesser;

class DatetimeGuesser extends AbstractGuesser implements GuesserInterface
{
    public function supports($mapping)
    {
        $mapping = array_merge([ 'type' => null ], $mapping);

        return in_array($mapping['type'], ['datetime', 'date', 'time']);
    }

    public function transform($str, $mapping = null)
    {
        $time = strtotime($str);

        if (false === $time) {
            throw new \Exception(sprintf('"%s" is not a supported date/time/datetime format. To know which formats are supported, please visit http://www.php.net/manual/en/datetime.formats.php', $str));
        }

        return \DateTime::createFromFormat('U', $time);
    }

    public function getName()
    {
        return 'datetime';
    }
}
