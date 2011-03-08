<?php
class Net_SylvainBoucher_Models_CollectionTest extends PHPUnit_Framework_TestCase
{
    protected $_collection;
    public function setUp()
    {
        $this->_collection = new Net\SylvainBoucher\Models\Collection();
    }
    public function testAddAndGetEntity()
    {
        $entity = new ConcreteEntity2();
        $this->_collection->addEntity($entity);
        foreach($this->_collection as $ent) {
            $this->assertSame($entity, $ent);
        }
    }
    public function testCount()
    {
        $entity = new ConcreteEntity2();
        $this->_collection->addEntity($entity);
        $this->assertTrue(count($this->_collection) === 1);
    }
    public function testToArray()
    {
        $entity = new ConcreteEntity2();
        $this->_collection->addEntity($entity);
        $array = $this->_collection->toArray();
        $this->assertTrue(is_array($array));
        $this->assertSame($array[0], $entity);
    }
    public function testSeekable()
    {
        $entity = new ConcreteEntity2();
        $entity2 = new ConcreteEntity2();
        $this->_collection->addEntity($entity);
        $this->_collection->addEntity($entity2);
        $this->_collection->seek(1);
        $result = $this->_collection->current();
        $this->assertSame($entity2, $result);
    }
    /**
     * @expectedException \Net\SylvainBoucher\Models\Exception\OutOfBoundsException
     */
    public function testSeekInvalidKey()
    {
        $this->_collection->seek(4);
    }
    public function testkey()
    {
        $entity = new ConcreteEntity2();
        $entity2 = new ConcreteEntity2();
        $this->_collection->addEntity($entity);
        $this->_collection->addEntity($entity2);
        $this->_collection->seek(1);
        $this->assertTrue(1 === $this->_collection->key());
    }
}
class ConcreteEntity2 extends Net\SylvainBoucher\Models\EntityAbstract
{
    
}