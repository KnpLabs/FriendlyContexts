<?php

namespace Knp\FriendlyExtension\Table;

use Knp\FriendlyExtension\Table\Node;
use Knp\FriendlyExtension\Table\NodeCollection;

class NodeFactory
{
    public function createNode()
    {
        return new Node;
    }

    public function createCollection()
    {
        return new NodeCollection;
    }
}
