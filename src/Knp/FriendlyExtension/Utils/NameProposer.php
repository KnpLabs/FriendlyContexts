<?php

namespace Knp\FriendlyExtension\Utils;

use Doctrine\Common\Inflector\Inflector;
use Knp\FriendlyExtension\Utils\TextFormater;

class NameProposer
{
    private $formater;

    public function __construct(TextFormater $formater)
    {
        $this->formater = $formater;
    }

    public function match($subject, $expected, $pluralize = false)
    {
        $proposals = $this->buildProposals($expected, $pluralize);

        return in_array($subject, $proposals);
    }

    public function buildProposals($name, $pluralize = false)
    {
        $proposals = [
            $name,
            $this->formater->toCamelCase($name),
            $this->formater->toUnderscoreCase($name),
            $this->formater->toSpaceCase($name),
        ];

        if (true === $pluralize) {
            $proposals = array_merge(
                array_map(function ($e) { return Inflector::singularize($e); }, $proposals),
                array_map(function ($e) { return Inflector::pluralize($e); }, $proposals)
            );
        }

        $proposals = array_merge(
            $proposals,
            array_map('strtoupper', $proposals),
            array_map('strtolower', $proposals)
        );

        $proposals = array_unique($proposals);
        sort($proposals);

        return $proposals;
    }
}
