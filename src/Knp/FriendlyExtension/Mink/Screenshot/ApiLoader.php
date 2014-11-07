<?php

namespace Knp\FriendlyExtension\Mink\Screenshot;

use Knp\FriendlyExtension\Context\Helper\ApiHelper;
use Knp\FriendlyExtension\Http\HttpContentTypeGuesser;
use Knp\FriendlyExtension\Mink\Screenshot\Loader;

class ApiLoader implements Loader
{
    public function __construct(ApiHelper $api, HttpContentTypeGuesser $typeGuesser)
    {
        $this->api         = $api;
        $this->typeGuesser = $typeGuesser;
    }

    public function supports()
    {
        try {
            $response = $this->api->getResponse();

            return null !== $response;
        } catch (\RuntimeException $ex) {

            return false;
        }
    }

    public function take()
    {
        return $this->api->getResponse()->getBody();
    }

    public function getExtension()
    {
        return $this->typeGuesser->getKey($this->getMimeType());
    }

    public function getMimeType()
    {
        $types = $this->api->getResponse()->getContentType();

        foreach (explode(';', $types) as $type) {

            return $type;
        }

        return 'text/html';
    }
}
