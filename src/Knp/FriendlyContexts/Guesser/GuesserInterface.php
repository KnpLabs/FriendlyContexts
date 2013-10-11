<?php

namespace Knp\FriendlyContexts\Guesser;

interface GuesserInterface
{
    public function supports($mapping);
    public function transform($str, $mapping);
    public function getManager();
    public function setManager(GuesserManager $manager);
    public function setFakers(array $fakers = null);
    public function getName();
}
