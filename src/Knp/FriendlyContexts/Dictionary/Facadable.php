<?php

namespace Knp\FriendlyContexts\Dictionary;

use Knp\FriendlyContexts\FacadeProvider;

trait Facadable
{
    protected $facadeProvider;

    public function getFacadeProvider()
    {
        return $this->facadeProvider;
    }

    public function setFacadeProvider(FacadeProvider $facadeProvider)
    {
        $this->facadeProvider = $facadeProvider;

        return $this;
    }

    public function getDeps($name)
    {
        return $this->facadeProvider->getDeps($name);
    }

    public function setDeps($name, $value)
    {
        $this->facadeProvider->setDeps($name, $value);

        return $this;
    }
}
