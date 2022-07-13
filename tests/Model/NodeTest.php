<?php


namespace OptimacrosTest\Model;


use Optimacros\Model\Node;
use Optimacros\Model\NodeInterface;
use PHPUnit\Framework\TestCase;

class NodeTest extends TestCase
{
    public function testNode()
    {
        $node = new Node('item name', Node::TYPE_COMPONENTS, 'parent', 'relation');

        $this->assertInstanceOf(NodeInterface::class, $node);

        $this->assertEquals('item name', $node->getItemName());
        $this->assertEquals(Node::TYPE_COMPONENTS, $node->getType());
        $this->assertTrue($node->hasParent());
        $this->assertEquals('parent', $node->getParent());
        $this->assertTrue($node->isRelation());
        $this->assertEquals('relation', $node->getRelation());

        $node = new Node('item name', Node::TYPE_PRODUCTS);
        $this->assertFalse($node->hasParent());
        $this->assertFalse($node->isRelation());

        $node = new Node('item name', Node::TYPE_EQUIPMENT, 'parent', 'rel');
        $this->assertFalse($node->isRelation());
    }

    public function testAppendNode()
    {
        $root = new Node('first', Node::TYPE_PRODUCTS);

        $root->append(new Node('second1', Node::TYPE_PRODUCTS, 'first'));
        $root->append(new Node('second2', Node::TYPE_PRODUCTS, 'first'));
        $root->append(new Node('second3', Node::TYPE_PRODUCTS, 'first'));
        $root->append(new Node('second4', Node::TYPE_PRODUCTS, 'first'));

        $root->append(new Node('tree', Node::TYPE_PRODUCTS, 'second1'));
        $root->append(new Node('tree1', Node::TYPE_PRODUCTS, 'second1'));

        $this->assertTrue($root->hasChildren());
        $this->assertCount(4, $root->getChildren());

        $this->assertTrue($root->getChildren()[0]->hasChildren());
        $this->assertCount(2, $root->getChildren()[0]->getChildren());

        $this->assertFalse($root->getChildren()[1]->hasChildren());
        $this->assertCount(0, $root->getChildren()[1]->getChildren());

        $this->assertFalse($root->getChildren()[2]->hasChildren());
        $this->assertCount(0, $root->getChildren()[2]->getChildren());

        $this->assertFalse($root->getChildren()[3]->hasChildren());
        $this->assertCount(0, $root->getChildren()[3]->getChildren());


        $root->append(new Node('tree41', Node::TYPE_PRODUCTS, 'second4'));

        $tree = new Node('tree42', Node::TYPE_PRODUCTS, 'second4');
        $tree->append(new Node('four1', Node::TYPE_PRODUCTS, 'tree42'));
        $tree->append(new Node('four2', Node::TYPE_PRODUCTS, 'tree42'));

        $root->append($tree);

        $this->assertTrue($root->getChildren()[3]->hasChildren());
        $this->assertCount(2, $root->getChildren()[3]->getChildren());


    }

    public function testAppendRelation()
    {
        $root = new Node('first', Node::TYPE_PRODUCTS);

        $root->append(new Node('second1', Node::TYPE_PRODUCTS, 'first'));
        $root->append(new Node('second2', Node::TYPE_PRODUCTS, 'first'));

        $root->append(new Node('tree', Node::TYPE_PRODUCTS, 'second1'));
        $root->append(new Node('tree1', Node::TYPE_COMPONENTS, 'second1', 'relation'));

        $relation = new Node('relation1', Node::TYPE_PRODUCTS, 'relation');
        $relation->append(new Node('rel1', Node::TYPE_PRODUCTS, 'relation1'));
        $relation->append(new Node('rel2', Node::TYPE_PRODUCTS, 'relation1'));

        $relation->append(new Node('rel2', Node::TYPE_COMPONENTS, 'relation1', 'relation123'));

        $root->append($relation);

        $root->append(new Node('name', Node::TYPE_EQUIPMENT, 'relation123'));

        $this->assertTrue($root->hasChildren());
        $this->assertCount(2, $root->getChildren());

        $this->assertTrue($root->getChildren()[0]->hasChildren());
        $this->assertFalse($root->getChildren()[1]->hasChildren());

        $this->assertCount(2, $root->getChildren()[0]->getChildren());

        $this->assertTrue($root->getChildren()[0]->getChildren()[1]->hasChildren());
        $this->assertCount(1, $root->getChildren()[0]->getChildren()[1]->getChildren());

        $this->assertTrue($root->getChildren()[0]->getChildren()[1]->getChildren()[0]->hasChildren());
        $this->assertCount(3, $root->getChildren()[0]->getChildren()[1]->getChildren()[0]->getChildren());

        $this->assertTrue($root->getChildren()[0]->getChildren()[1]->getChildren()[0]->getChildren()[2]->hasChildren());
        $this->assertCount(1, $root->getChildren()[0]->getChildren()[1]->getChildren()[0]->getChildren()[2]->getChildren());
    }
}