<?php

namespace Knp\FriendlyContexts\Guesser;

class DatetimeGuesser extends AbstractGuesser implements GuesserInterface
{
    public function supports(array $mapping)
    {
        $mapping = array_merge([ 'type' => null ], $mapping);

        return in_array($mapping['type'], ['datetime', 'date', 'time']);
    }

    public function transform($str, array $mapping = null)
    {
        try {
            return new \DateTime($str);
        } catch (\Exception $e) {
            throw new \Exception(sprintf('"%s" is not a supported date/time/datetime format. To know which formats are supported, please visit http://www.php.net/manual/en/datetime.formats.php', $str));
        }
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
