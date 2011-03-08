<?php
class Net_SylvainBoucher_Paginator_PaginatorAdapterTest extends PHPUnit_Framework_TestCase
{
    protected $_select;
    protected $_paginatorAdapter;

    public function setUp()
    {
        $this->_select = new Concrete_Db_Select(new ConcreteAdapter());
        $this->_paginatorAdapter =
                new Net\SylvainBoucher\Paginator\PaginatorAdapter(
                        $this->_select,
                        'ConcreteEntity'
                );
    }
    public function testGetItemsInstanceofCollection()
    {
        $collection = $this->_paginatorAdapter->getItems(1, 1);
        $this->assertTrue($collection instanceof Net\SylvainBoucher\Models\CollectionInterface);
    }
    public function testGetItemsContainsInstancesOfEntityInterface()
    {
       $collection = $this->_paginatorAdapter->getItems(1, 1);
       foreach ($collection as $entity) {
        $this->assertTrue($entity instanceof Net\SylvainBoucher\Models\EntityInterface);
       }
    }
    public function testEntityGotPopulated()
    {
        $collection = $this->_paginatorAdapter->getItems(1, 1);
       foreach ($collection as $entity) {
        $this->assertTrue(isset($entity->id) && !empty($entity->id));
       }
    }
}
class Concrete_Db_Select extends Zend_Db_Select
{
    public function query($fetchMode = null, $bind = array())
    {
        return $this;
    }
    public function limit($sql, $count, $offset = 0)
    {

    }
    public function fetchAll()
    {
        return array(
            array('id' => 1),
            array('id' => 2),
            array('id' => 3)
        );
    }
}
class ConcreteAdapter extends Zend_Db_Adapter_Mysqli
{
    public function __construct()
    {
        
    }
}
class ConcreteEntity extends Net\SylvainBoucher\Models\EntityAbstract
{
    public $id;
    public function fromArray(array $array)
    {
        $this->id = $array['id'];
    }
}