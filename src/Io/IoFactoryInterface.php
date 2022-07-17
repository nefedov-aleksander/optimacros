<?php


namespace Optimacros\Io;


interface IoFactoryInterface
{

    public function createCsvReader(string $filename): ReaderInterface;

    public function createCsvWriter(string $filename): WriterInterface;

}