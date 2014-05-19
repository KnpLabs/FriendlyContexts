<?php

namespace Knp\FriendlyContexts\Mink\Screenshot;

class Screenshot
{
    protected $data;

    protected $format;

    public function __construct($data, $format)
    {
        $this->data = $data;
        $this->format = $format;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getFormat()
    {
        return $this->format;
    }
}
