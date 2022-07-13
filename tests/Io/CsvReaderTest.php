<?php


namespace OptimacrosTest\Io;


use Optimacros\Io\CsvReader;
use PHPUnit\Framework\TestCase;

class CsvReaderTest extends TestCase
{
    public function testCsvRearedReadFile()
    {
        $reader = new CsvReader(__DIR__ . '/fixtures/test_read_line.txt');

        $data = $reader->current();

        $this->assertCount(4, $data);
    }

    public function testCsvReaderSkipHeadLine()
    {
        $reader = new CsvReader(__DIR__ . '/fixtures/test_head_line.txt');

        $data = $reader->current();

        $this->assertEquals(12, $data[0]);
        $this->assertEquals('test', $data[1]);
        $this->assertEquals('test test', $data[2]);
    }

    public function testCsvReaderSkipEmptyLines()
    {
        $reader = new CsvReader(__DIR__ . '/fixtures/test_empty_lines.txt');

        $data = $reader->current();

        $this->assertEquals(12, $data[0]);
        $this->assertEquals('test', $data[1]);
        $this->assertEquals('test test', $data[2]);
    }

    public function testCsvReaderReadBadFile()
    {
        $this->expectException(\RuntimeException::class);

        $reader = new CsvReader(__DIR__ . '/fixtures/not_found.txt');
    }
}