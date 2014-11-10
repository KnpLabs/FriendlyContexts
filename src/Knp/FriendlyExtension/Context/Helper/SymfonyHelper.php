<?php

namespace Knp\FriendlyExtension\Context\Helper;

use Symfony\Component\HttpKernel\KernelInterface;

class SymfonyHelper extends AbstractHelper
{
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function getName()
    {
        return 'symfony';
    }

    public function get($id)
    {
        return $this->getContainer()->get($id);
    }

    public function getParameter($id)
    {
        return $this->getContainer()->getParameter($id);
    }

    private function getContainer()
    {
        return $this->kernel->getContainer();
    }
}
