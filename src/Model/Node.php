<?php


namespace Optimacros\Model;


class Node implements NodeInterface
{
    const TYPE_PRODUCTS = 'Изделия и компоненты';
    const TYPE_EQUIPMENT = 'Варианты комплектации';
    const TYPE_COMPONENTS = 'Прямые компоненты';

    private $itemName;
    private $type;
    private $parent;
    private $relation;

    private $children;

    public function __construct(string $itemName, string $type, string $parent = '', string $relation = '')
    {
        $this->itemName = $itemName;
        $this->type = $type;
        $this->parent = $parent;
        $this->relation = $relation;

        $this->children = new \SplDoublyLinkedList();
        $this->children->setIteratorMode(\SplDoublyLinkedList::IT_MODE_FIFO | \SplDoublyLinkedList::IT_MODE_KEEP);
    }

    public function getItemName(): string
    {
        return $this->itemName;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function hasParent(): bool
    {
        return !empty(trim($this->parent));
    }

    public function getParent(): string
    {
        return $this->parent;
    }

    public function isRelation(): bool
    {
        return $this->getType() == self::TYPE_COMPONENTS && !empty(trim($this->relation));
    }

    public function getRelation(): string
    {
        return $this->relation;
    }

    public function append(NodeInterface $node): bool
    {
        if($this->isRelation())
        {
            if($this->getRelation() == $node->getParent())
            {
                $this->children->push($node);
                return true;
            }
        }
        else
        {
            if($this->getItemName() == $node->getParent())
            {
                $this->children->push($node);
                return true;
            }
        }


        foreach ($this->getChildren() as $child)
        {
            if($child->append($node))
            {
                return true;
            }
        }

        return false;
    }

    public function getChildren(): \SplDoublyLinkedList
    {
        return $this->children;
    }

    public function hasChildren(): bool
    {
        return !$this->children->isEmpty();
    }
}