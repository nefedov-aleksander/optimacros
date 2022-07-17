<?php

namespace Optimacros\Io;


use Optimacros\Model\NodeInterface;

class CsvWriter implements WriterInterface
{

    private $stream;

    private $delimiter;

    public function __construct(string $filename, $delimiter = ';')
    {
        $this->delimiter = $delimiter;

        $this->stream = fopen($filename, 'w');
    }

    public function write(NodeInterface $node)
    {
        fputcsv($this->stream, [
            $node->getItemName(),
            $node->getType(),
            $node->getParent(),
            $node->getRelation()
        ], $this->delimiter);
    }

    public function __destruct()
    {
        fclose($this->stream);
    }
}