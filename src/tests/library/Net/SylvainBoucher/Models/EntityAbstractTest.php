<?php
class Net_SylvainBoucher_Models_EntityAbstractTest extends PHPUnit_Framework_TestCase
{
    protected $_concreteEntity;
    protected $_data = array(
        'id' => 4,
        'foo' => null,
        'bar' => null,
        'baz' => null
    );
    public function setUp()
    {
        $this->_concreteEntity = new ConcreteEntityAbstract();
    }
    public function testToArray()
    {
        $this->_concreteEntity->setId(4);
        $this->assertSame($this->_data, $this->_concreteEntity->toArray());
    }
    public function testFromArray()
    {
        $this->_concreteEntity->fromArray($this->_data);
        $this->assertSame($this->_data, $this->_concreteEntity->toArray());
    }
    public function testSetGetId()
    {
        $this->_concreteEntity->setId(4);
        $this->assertSame(4, $this->_concreteEntity->getId());
    }
    /**
     * @expectedException \Net\SylvainBoucher\Models\Exception\OutOfBoundsException
     */
    public function testGetIdThrowExceptionifNotSet()
    {
        $entity = new BadEntityAbstract();
        $id = $entity->getId();
    }
    public function testCount()
    {
        $array = array(
            'id' => 3,
            'baz' => 'foo'
        );
        $this->_concreteEntity->fromArray($array);
        $this->assertSame(count($this->_concreteEntity), count($array));
    }
    public function testIssetunset()
    {
        $array = array(
            'id' => 3,
            'baz' => 'foo'
        );
        $this->_concreteEntity->fromArray($array);
        $this->assertTrue(isset($this->_concreteEntity->baz));
        unset($this->_concreteEntity->baz);
        $this->assertTrue(!isset($this->_concreteEntity->baz));
    }
    /**
     * @expectedException \Net\SylvainBoucher\Models\Exception\OutOfBoundsException
     */
    public function testThrowExceptionIfKeyNotSet()
    {
        $this->_concreteEntity->nonsetkey = 'value';
    }
    public function testGetValue()
    {
        $array = array(
            'id' => 3,
            'baz' => 'foo'
        );
        $this->_concreteEntity->fromArray($array);
        $this->assertSame($this->_concreteEntity->baz, 'foo');
        $this->assertSame($this->_concreteEntity->unsetKey, null);
    }
}
class ConcreteEntityAbstract extends \Net\SylvainBoucher\Models\EntityAbstract
{
    protected $_data = array(
        'id' => null,
        'foo' => null,
        'bar' => null,
        'baz' => null
    );
}
class BadEntityAbstract extends \Net\SylvainBoucher\Models\EntityAbstract
{
    protected $_data = array();
}