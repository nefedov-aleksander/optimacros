<?php


namespace Optimacros\Io;


class IoFactory implements IoFactoryInterface
{

    public function createCsvReader(string $filename): ReaderInterface
    {
        return new CsvReader($filename);
    }

    public function createCsvWriter(string $filename): WriterInterface
    {
        return new CsvWriter($filename);
    }
}