<?php

namespace Knp\FriendlyExtension\Type\Guesser;

use Knp\FriendlyExtension\Type\GuesserRegistry;

interface GuesserInterface
{
    public function supports(array $mapping);

    public function transform($str, array $mapping);

    public function fake(array $mapping);

    public function getManager();

    public function setManager(GuesserRegistry $manager);

    public function getName();
}
