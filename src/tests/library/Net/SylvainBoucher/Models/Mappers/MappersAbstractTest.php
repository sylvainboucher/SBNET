<?php
class Net_SylvainBoucher_Models_Mappers_MappersAbstractTest extends PHPUnit_Framework_TestCase
{
    protected $_concreteMapperAbstract;
    public function setUp()
    {
        $this->_concreteMapperAbstract = new ConcreteMapperAbstract('ConcreteEntity', new ConcreteDbTable());
    }
    /**
     * @expectedException \Net\SylvainBoucher\Models\Mappers\Exception\InvalidArgumentException
     */
    public function testThrowExceptionIfGatewayNoZendDbTable()
    {
        $concreteMapperAbstract = new ConcreteMapperAbstract('ConcreteEntity', new stdClass());
        $concreteMapperAbstract->delete(4);
    }
    public function testGetGenericGateway()
    {
        $gateway = array('mygateway');
        $concreteMapperAbstract = new ConcreteMapperAbstract('ConcreteEntity', $gateway);
        $this->assertSame($gateway, $concreteMapperAbstract->getGateway());
    }
    public function testFindReturnEntity()
    {
        $entity = new ConcreteEntity();
        $entity2 = new ConcreteEntity();
        $array['id'] = 3;
        $entity2 = $entity2->fromArray($array);
        $newEntity = $this->_concreteMapperAbstract->find(4, new ConcreteEntity());
        $this->assertSame($entity2, $newEntity);
    }
    public function testFindReturnFalseIfNoMatch()
    {
        $dbTable = new ConcreteDbTable();
        $dbTable->setReturnNull();
        $concreteMapperAbstract = new ConcreteMapperAbstract('ConcreteEntity', $dbTable);
        $newEntity = $concreteMapperAbstract->find(0, new ConcreteEntity());
        $this->assertTrue(false === $newEntity);
    }
    public function testDeleteReturnAffectedRows()
    {
        $affectedRows = $this->_concreteMapperAbstract->delete(4);
        $this->assertSame(1, $affectedRows);
    }
    public function testFetchAllReturnsPaginator()
    {
        $this->assertTrue($this->_concreteMapperAbstract->fetchAll(1, 1) instanceof Zend_Paginator);
    }
    public function testSave()
    {
        $entity = new EntityForMapper();
        $result = $this->_concreteMapperAbstract->save($entity);
        $this->assertSame($result->getId(), 5);
    }
    public function testUpdate()
    {
        $entity = new EntityForMapper();
        $entity->fromArray(array('id' => 2));
        $result = $this->_concreteMapperAbstract->save($entity);
        $this->assertSame($result->getId(), 2);
    }
    public function testGetPaginatorInstance()
    {
        $dbTable = new GenericDbTable();
        $genericMapperAbstract = new GenericMapperAbstract('ConcreteEntity', $dbTable);
        $paginator = $genericMapperAbstract
                          ->getPaginatorInstance(
                                  new Zend_Db_Select(
                                          new Zend_Db_Adapter_Mysqli(
                                            array(
                                                  'dbname' => 'sandbox',
                                                  'password' => 'bla',
                                                  'username' => 'username'
                                              )
                                           )
                                  )
                            );
        $this->assertTrue($paginator instanceof Zend_Paginator);
    }
}
class ConcreteMapperAbstract extends \Net\SylvainBoucher\Models\Mappers\MapperAbstract
{
    public function getGateway()
    {
        return $this->_getGateway();
    }
    public function  getPaginatorInstance($select) {
        return new Zend_Paginator(new Zend_Paginator_Adapter_Null(2));
    }
}
class GenericMapperAbstract extends \Net\SylvainBoucher\Models\Mappers\MapperAbstract
{

}
class GenericDbTable extends Zend_Db_Table_Abstract
{

}
class ConcreteDbTable extends Zend_Db_Table_Abstract
{
    protected $_returnNull = false;
    public function setReturnNull()
    {
        $this->_returnNull = true;
    }
    public function select($withFromPart = self::SELECT_WITHOUT_FROM_PART)
    {
        return $this;
    }
    public function where()
    {
        return $this;
    }
    public function fetchRow($where = null, $order = null, $offset = null)
    {
        if ($this->_returnNull) {
            return null;
        }
        return new ConcreteRow();
    }
    public function delete($where)
    {
        return 1;
    }
    public function getAdapter()
    {
        return $this;
    }
    public function quoteInto()
    {
        return $this;
    }
    public function insert(array $data)
    {
        return 5;
    }
    public function update(array $data, $where)
    {
        
    }
}
class ConcreteRow
{
    public function toArray()
    {
        $array['id'] = 3;
        return $array;
    }
}
class EntityForMapper extends \Net\SylvainBoucher\Models\EntityAbstract
{
    protected $_data = array(

       'id' => null,
        'foo' => null
    );
}
