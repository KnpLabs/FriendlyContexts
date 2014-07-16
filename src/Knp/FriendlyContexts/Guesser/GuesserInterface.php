<?php

namespace Knp\FriendlyContexts\Guesser;

interface GuesserInterface
{
    public function supports(array $mapping);
    public function transform($str, array $mapping);
    public function fake(array $mapping);
    public function getManager();
    public function setManager(GuesserManager $manager);
    public function getName();
}
