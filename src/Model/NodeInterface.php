<?php


namespace Optimacros\Model;


interface NodeInterface
{
    public function getItemName(): string;

    public function getType(): string;

    public function hasParent(): bool;

    public function getParent(): string;

    public function isRelation(): bool;

    public function getRelation(): string;

    public function append(NodeInterface $node): bool;

    public function getChildren(): \SplDoublyLinkedList;

    public function hasChildren(): bool;

}