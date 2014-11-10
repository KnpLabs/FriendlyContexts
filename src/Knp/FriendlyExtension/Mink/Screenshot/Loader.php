<?php

namespace Knp\FriendlyExtension\Mink\Screenshot;

interface Loader
{
    public function supports();
    public function take();
    public function getExtension();
    public function getMimeType();
}
