<?php


namespace Optimacros\Builder;


use Optimacros\Model\NodeInterface;

interface RelationBuilderInterface
{
    public function defineRelations(): array;

    public function isRelationDefined(string $name): bool;

    public function getRelation(string $name): NodeInterface;


}