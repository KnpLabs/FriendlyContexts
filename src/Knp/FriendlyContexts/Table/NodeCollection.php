<?php

namespace Knp\FriendlyContexts\Table;

use Knp\FriendlyContexts\Table\Node;

class NodeCollection
{
    private $nodes = [];

    public function getNodes()
    {
        return array_reduce(
            $this->nodes,
            function ($previous, $line) {
                return array_merge($previous, $line);
            },
            []
        );
    }

    public function addNode(Node $node, $line, $column)
    {
        $this->nodes[$line][$column] = $node;

        return $this;
    }

    public function atPosition($line = null, $column = null)
    {
        switch (true) {
            case null === $line && null === $column:
                return $this->getNodes();
            case null !== $line && null === $column:
                return array_key_exists($line, $this->nodes)
                    ? $this->nodes[$line]
                    : []
                ;
            case null === $line && null !== $column:
                $nodes = [];
                foreach ($this->nodes as $i => $nodes) {
                    if (array_key_exists($column, $nodes)) {
                        $nodes[$i] = $nodes[$column];
                    }
                }
                return $nodes;
            case null !== $line && null !== $column:
                $nodes = $this->atPosition($line);
                return array_key_exists($column, $nodes)
                    ? $nodes[$column]
                    : null
                ;
        }
    }

    public function search($content)
    {
        return array_filter(
            $this->getNodes(),
            function ($e) use ($content) {
                return $e->getContent() === $content;
            }
        );
    }
}
