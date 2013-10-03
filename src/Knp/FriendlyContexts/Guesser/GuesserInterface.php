<?php

namespace Knp\FriendlyContexts\Guesser;

interface GuesserInterface
{
    public function supports($mapping);
    public function transform($str);
}
