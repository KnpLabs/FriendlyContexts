<?php

namespace Knp\FriendlyExtension\Table;

use Knp\FriendlyExtension\Table\NodeFactory;

class NodesBuilder
{
    public function __construct(NodeFactory $factory)
    {
        $this->factory = $factory;
    }

    public function build(array $table)
    {
        $collection = $this->factory->createCollection();
        $table = array_values($table);
        foreach ($table as $line => $columns) {
            $columns = array_values($columns);
            foreach ($columns as $column => $content) {
                $node = $this->factory->createNode();
                $collection->addNode($node, $line, $column);
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
