<?php


namespace OptimacrosTest\Builder;


use Optimacros\Builder\RelationBuilder;
use Optimacros\Builder\RelationBuilderInterface;
use Optimacros\Io\CsvReader;
use Optimacros\Io\CsvWriter;
use Optimacros\Io\IoFactory;
use PHPUnit\Framework\TestCase;

class RelationBuilderTest extends TestCase
{
    public function testRelationBuilderInstanceOfRelationBuilderInterface()
    {
        $reader = $this->createMock(CsvReader::class);
        $this->assertInstanceOf(RelationBuilderInterface::class, new RelationBuilder($reader, new IoFactory(), '/cache'));
    }

//    public function testRelationBuilderDefineRelations()
//    {
//        $data = [
//            ["Тележка Б25.#2", "Прямые компоненты" , "Стандарт.#1", "Тележка Б25"],
//            ["Стандарт.#101", "Варианты комплектации", "ХОП 101", ""],
//            ["Тележка Б25.#205", "Прямые компоненты", "Стандарт.#101", "Тележка Б25"],
//            ["Тележка Б25", "Изделия и компоненты", "Total", ""],
//            ["Стандарт.#5", "Варианты комплектации", "Тележка Б25", ""],
//            ["РБ ЦДЛР.9855.00.02.000.#17", "Прямые компоненты", "Стандарт.#5", "РБ ЦДЛР.9855.00.02.000"],
//            ["БН ЦДЛР.9855.00.01.000.#18", "Прямые компоненты", "Стандарт.#5", "БН ЦДЛР.9855.00.01.000"]
//        ];
//        $reader = $this->createMock(CsvReader::class);
//
//        $reader->expects($this->exactly(7))
//            ->method('current')
//            ->will($this->onConsecutiveCalls(...$data));
//
//        $reader->method('valid')
//            ->will($this->onConsecutiveCalls(true, true, true, true, true, true, true, false));
//
//        $reader->expects($this->once())->method('rewind');
//
//        $builder = new RelationBuilder($reader, new IoFactory(), '/cache');
//
//        $this->assertCount(3, $builder->defineRelations());
//
//        $this->assertTrue($builder->isRelationDefined('Тележка Б25'));
//        $this->assertTrue($builder->isRelationDefined('РБ ЦДЛР.9855.00.02.000'));
//        $this->assertFalse($builder->isRelationDefined('Тележка Б25.#205'));
//    }

    public function testBuildCacheRelations()
    {
        $data = [
            ["Стандарт.#1", "Варианты комплектации", "ПВЛ", ""],
            ["Тележка Б25.#2", "Прямые компоненты", "Стандарт.#1", "Тележка Б25"],
            ["ЦИС суг соч 163,1м Б25", "Изделия и компоненты", "Total", ""],
            ["ЦИС хлор Б25", "Изделия и компоненты", "Total", ""],
            ["Котел ЦИС пр нрж", "Изделия и компоненты", "Total", ""],
            ["БН ЦДЛР.9855.00.01.000.#18", "Прямые компоненты", "Стандарт.#5", "БН ЦДЛР.9855.00.01.000"],
            ["Тележка Б25", "Изделия и компоненты", "Total", ""],
            ["Стандарт.#5", "Варианты комплектации", "Тележка Б25", ""],
            ["РБ ЦДЛР.9855.00.02.000.#17", "Прямые компоненты", "Стандарт.#5", "РБ ЦДЛР.9855.00.02.000"],
            ["Колесная пара 25 т.#19", "Прямые компоненты", "Стандарт.#5", "Колесная пара 25 т"],
            ["Колесо 25т", "Изделия и компоненты", "Total", ""],
            ["Колесная пара 25 т", "Изделия и компоненты", "Total", ""],
            ["Стандарт.#10", "Варианты комплектации", "Колесная пара 25 т", ""],
            ["Колесо 25т.#52", "Прямые компоненты", "Стандарт.#10", "Колесо 25т"],
            ["Ось 25т.#53", "Прямые компоненты", "Стандарт.#10", "Ось 25т"]
        ];
        // rewind after defined names
        $rewinds = 1;

        // for search Тележка Б25 and retrive and cache
        $defineSt5 = array_merge([
            ["Стандарт.#1 asdfa sdf", "Варианты комплектации", "ПВЛ", ""],
            ["Тележка Б25.#2", "Прямые компоненты", "Стандарт.#1", "Тележка Б25"],
            ["ЦИС суг соч 163,1м Б25", "Изделия и компоненты", "Total", ""],
            ["ЦИС хлор Б25", "Изделия и компоненты", "Total", ""],
            ["Котел ЦИС пр нрж", "Изделия и компоненты", "Total", ""],
            ["Тележка Б25", "Изделия и компоненты", "Total", ""],
            ["Стандарт.#5", "Варианты комплектации", "Тележка Б25", ""]
        ], $data);
        $rewinds+=2; // rewind after finds Тележка Б25 and retrive tree

        // for search БН ЦДЛР.9855.00.01.000 with not found components
        $rewinds++;
        // for search РБ ЦДЛР.9855.00.02.000 with not found components
        $rewinds++;

        // for search Колесная пара 25 т and retrive and cache
        $defineSt10 = array_merge([
            ["Стандарт.#1", "Варианты комплектации", "ПВЛ", ""],
            ["Тележка Б25.#2", "Прямые компоненты", "Стандарт.#1", "Тележка Б25"],
            ["ЦИС суг соч 163,1м Б25", "Изделия и компоненты", "Total", ""],
            ["ЦИС хлор Б25", "Изделия и компоненты", "Total", ""],
            ["Котел ЦИС пр нрж", "Изделия и компоненты", "Total", ""],
            ["БН ЦДЛР.9855.00.01.000.#18", "Прямые компоненты", "Стандарт.#5", "БН ЦДЛР.9855.00.01.000"],
            ["Тележка Б25", "Изделия и компоненты", "Total", ""],
            ["Стандарт.#5", "Варианты комплектации", "Тележка Б25", ""],
            ["РБ ЦДЛР.9855.00.02.000.#17", "Прямые компоненты", "Стандарт.#5", "РБ ЦДЛР.9855.00.02.000"],
            ["Колесная пара 25 т.#19", "Прямые компоненты", "Стандарт.#5", "Колесная пара 25 т"],
            ["Колесо 25т", "Изделия и компоненты", "Total", ""],
            ["Колесная пара 25 т", "Изделия и компоненты", "Total", ""],
            ["Стандарт.#10", "Варианты комплектации", "Колесная пара 25 т", ""]
        ], $data);
        $rewinds+=2; // rewind after finds Колесная пара 25 and retrive tree

        // for search Колесо 25т with not found components
        $rewinds++;

        // for search Ось 25т with not found components
        $rewinds++;

        $total = array_merge(
            $data, //defined names
            $defineSt5, // for search Тележка Б25 and retrive and cache,
            $data, // for search БН ЦДЛР.9855.00.01.000 with not found components
            $data, // for search РБ ЦДЛР.9855.00.02.000 with not found components
            $defineSt10, // for search Колесная пара 25 т and retrive and cache,
            $data, // for search Колесо 25т with not found components
            $data // for search Ось 25т with not found components
        );

        $ends = array_merge(
            array_fill(0, count($data), true), // define names
            [false],
            array_fill(0, count($defineSt5), true), // for search Тележка Б25 and retrive and cache
            [false],
            array_fill(0, count($data), true), // for search БН ЦДЛР.9855.00.01.000 with not found components
            [false],
            array_fill(0, count($data), true), // for search РБ ЦДЛР.9855.00.02.000 with not found components
            [false],
            array_fill(0, count($defineSt10), true), // for search Колесная пара 25 т and retrive and cache,
            [false],
            array_fill(0, count($data), true), // for search Колесо 25т with not found components
            [false],
            array_fill(0, count($data), true), // for search Ось 25т with not found components
            [false]
        );

        $reader = $this->createMock(CsvReader::class);
        $reader->method('current')->will($this->onConsecutiveCalls(...$total));
        $reader->method('valid')->will($this->onConsecutiveCalls(...$ends));
        $reader->expects($this->exactly($rewinds))->method('rewind');

        $cacheWriter1 = $this->createMock(CsvWriter::class);
        $cacheWriter1
            ->expects($this->exactly(4))
            ->method('write');
        $cacheWriter2 = $this->createMock(CsvWriter::class);
        $cacheWriter2
            ->expects($this->exactly(3))
            ->method('write');

        $ioFactory = $this->createMock(IoFactory::class);
        $ioFactory
            ->expects($this->exactly(2))
            ->method('createCsvWriter')
            ->will($this->onConsecutiveCalls($cacheWriter1, $cacheWriter2));

        $builder = new RelationBuilder($reader, $ioFactory, '/cache');

        $this->assertCount(6, $builder->defineRelations());






        $relation = $builder->getRelation('Колесная пара 25 т');
        $this->assertEquals('Стандарт.#10', $relation->getItemName());
        $this->assertCount(2, $relation->getChildren());

        $this->assertEquals('Колесо 25т.#52', $relation->getChildren()[0]->getItemName());
        $this->assertEquals('Ось 25т.#53', $relation->getChildren()[1]->getItemName());

        $this->assertCount(0, $relation->getChildren()[0]->getChildren());
        $this->assertCount(0, $relation->getChildren()[1]->getChildren());
    }
}