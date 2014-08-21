<?php

namespace Knp\FriendlyContexts\Table;

use Knp\FriendlyContexts\Table\Node;
use Knp\FriendlyContexts\Table\NodeCollection;

class NodesBuilder
{
    public function build(array $table)
    {
        $collection = new NodeCollection;
        foreach ($table as $line => $columns) {
            foreach ($columns as $column => $content) {
                $collection->addNode($node = new Node, $line, $column);
                $node->setContent($content);
                $node->setTop($collection->atPosition($line - 1, $column));
                $node->setBottom($collection->atPosition($line + 1, $column));
                $node->setLeft($collection->atPosition($line, $column - 1));
                $node->setLeft($collection->atPosition($line, $column + 1));
            }
        }

        return $collection;
    }
}
