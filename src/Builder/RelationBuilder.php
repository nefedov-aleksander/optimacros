<?php


namespace Optimacros\Builder;


use Optimacros\Io\IoFactoryInterface;
use Optimacros\Io\ReaderInterface;
use Optimacros\Model\Node;
use Optimacros\Model\NodeInterface;

class RelationBuilder implements RelationBuilderInterface
{
    private $reader;

    private $ioFactory;

    private $cacheDir;

    private $relationNames = [];

    public function __construct(
        ReaderInterface $reader,
        IoFactoryInterface $ioFactory,
        string $cacheDir
    )
    {
        $this->reader = $reader;
        $this->ioFactory = $ioFactory;
        $this->cacheDir = $cacheDir;
    }

    public function defineRelations(): array
    {
        $relationNames = [];
        foreach ($this->fetchNode() as $node)
        {
            if($node->isRelation())
            {
                $relationNames[] = $node->getRelation();
            }
        }

        $relationNames = array_unique($relationNames);

        $this->relationNames = array_combine($relationNames, array_map(function($x) {
            return md5($x);
        }, $relationNames));

        foreach ($this->relationNames as $name => $cacheFile)
        {

            foreach ($this->fetchNode() as $relation)
            {
                if($relation->hasParent() && $relation->getParent() == $name)
                {
                    $cache = $this->ioFactory->createCsvWriter($this->getCachePath($cacheFile));
                    $cache->write($relation);

                    foreach ($this->fetchNode() as $node)
                    {
                        if($node->hasParent() && $node->getParent() == $relation->getItemName())
                        {
                            $cache->write($node);
                        }
                    }

                    break;
                }

            }
        }

        return $this->relationNames;
    }

    public function isRelationDefined(string $name): bool
    {
        return array_key_exists($name, $this->relationNames);
    }

    public function getRelation(string $name): NodeInterface
    {
        if(!$this->isRelationDefined($name))
        {
//            throw new
        }

        $this->ioFactory->createCsvReader($this->getCachePath($this->relationNames[$name]));
    }

    private function fetchNode()
    {
        foreach ($this->reader as $node)
        {
            yield new Node(...$node);
        }
    }

    private function getCachePath($filename)
    {
        return "{$this->cacheDir}/{$filename}";
    }

    public function __destruct()
    {
        foreach ($this->relationNames as $name => $cacheFile)
        {
            file_exists($this->getCachePath($cacheFile)) && unlink($this->getCachePath($cacheFile));
        }
    }
}