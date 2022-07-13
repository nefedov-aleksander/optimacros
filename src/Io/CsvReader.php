<?php


namespace Optimacros\Io;


class CsvReader implements ReaderInterface
{
    private $stream;

    private $delimiter;

    private $skipHeadLine;

    private $skipEmptyLines;

    private $current;

    private $line = 0;

    public function __construct(string $filename, $delimiter = ';', $skipHeadLine = true, $skipEmptyLines = true)
    {
        $this->delimiter = $delimiter;
        $this->skipHeadLine = $skipHeadLine;
        $this->skipEmptyLines = $skipEmptyLines;

        if(!file_exists($filename))
        {
            throw new \RuntimeException("File {$filename} not found");
        }

        $this->stream = fopen($filename, 'r');

        if($this->stream === false)
        {
            throw new \RuntimeException("Unable to open file {$filename}");
        }

        $this->next();

        $this->skipEmptyLines();

        $this->skipHeadLine();
    }

    public function __destruct()
    {
        fclose($this->stream);
    }

    public function current()
    {
        return $this->current;
    }

    public function next()
    {
        if($this->valid())
        {
            $this->current = fgetcsv($this->stream, null, $this->delimiter);
            $this->line++;

            $this->skipEmptyLines();
        }
    }

    public function key()
    {
        return $this->line;
    }

    public function valid()
    {
        return !feof($this->stream);
    }

    public function rewind()
    {
        rewind($this->stream);

        $this->skipHeadLine();
    }

    private function skipHeadLine()
    {
        if($this->skipHeadLine)
        {
            $this->next();
        }
    }

    private function skipEmptyLines()
    {
        if($this->skipEmptyLines && $this->current[0] == null)
        {
            $this->next();
        }
    }
}