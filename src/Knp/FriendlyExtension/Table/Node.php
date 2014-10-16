<?php

namespace Knp\FriendlyExtension\Table;

class Node
{
    private $content;
    private $top;
    private $right;
    private $left;
    private $bottom;

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    public function getTop()
    {
        return $this->top;
    }

    public function setTop(Node $top = null)
    {
        $this->top = $top;

        if (null !== $top && $this !== $top->getBottom()) {
            $top->setBottom($this);
        }

        return $this;
    }

    public function getBottom()
    {
        return $this->bottom;
    }

    public function setBottom(Node $bottom = null)
    {
        $this->bottom = $bottom;

        if (null !== $bottom && $this !== $bottom->getTop()) {
            $bottom->setTop($this);
        }

        return $this;
    }

    public function getLeft()
    {
        return $this->left;
    }

    public function setLeft(Node $left = null)
    {
        $this->left = $left;

        if (null !== $left && $this !== $left->getRight()) {
            $left->setRight($this);
        }

        return $this;
    }

    public function getRight()
    {
        return $this->right;
    }

    public function setRight(Node $right = null)
    {
        $this->right = $right;

        if (null !== $right && $this !== $right->getLeft()) {
            $right->setLeft($this);
        }

        return $this;
    }

    public function equals(Node $other, $otherIsPartial = false)
    {
        if ($this->content !== $other->getContent()) {

            return false;
        }

        if (null !== $this->right && null !== $other->getRight() && false === $this->right->equals($other->getRight())) {

            return false;
        }

        if (null !== $this->bottom && null !== $other->getBottom() && false === $this->bottom->equals($other->getBottom())) {

            return false;
        }

        if (null === $this->right && null !== $other->getRight()) {

            return false;
        }

        if (null === $this->bottom && null !== $other->getBottom()) {

            return false;
        }

        if (true === $otherIsPartial) {

            return true;
        }

        if (null !== $this->right && null === $other->getRight()) {

            return false;
        }

        if (null !== $this->bottom && null === $other->getBottom()) {

            return false;
        }

        return true;
    }
}
