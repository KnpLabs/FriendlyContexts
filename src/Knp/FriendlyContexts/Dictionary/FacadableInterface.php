<?php

namespace Knp\FriendlyContexts\Dictionary;

use Knp\FriendlyContexts\FacadeProvider;

interface FacadableInterface
{
    public function getFacadeProvider();
    public function setFacadeProvider(FacadeProvider $facade);
}
