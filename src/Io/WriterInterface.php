<?php


namespace Optimacros\Io;


use Optimacros\Model\NodeInterface;

interface WriterInterface
{
    public function write(NodeInterface $node);
}