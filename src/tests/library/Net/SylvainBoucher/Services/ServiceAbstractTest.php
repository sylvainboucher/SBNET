<?php
class Net_SylvainBoucher_Services_ServiceAbstractTest extends PHPUnit_Framework_TestCase
{
    protected $_concreteServiceAbstract;
    protected $_mapper;
    protected $_cache;
    protected $_acl;
    public function setUp()
    {
        $this->_cache = $this->_getCache();
        $this->_mapper = $this->getMock(
            'Net\SylvainBoucher\Models\Mappers\MapperAbstract',
            array('delete', 'save', 'find', 'fetchAll'),
            array(),
            'ConcreteMapperForServices' . uniqid(),
            false
         );
        require_once APPLICATION_PATH . '/configs/Acl.php';
        $this->_acl = new \Application\Configs\Acl();
        $this->_acl->addResource('resource');
        $this->_acl->allow();
        $this->_concreteServiceAbstract = new ConcreteServiceAbstract(
                'ConcreteEntity',
                'resource',
                new Zend_Form(),
                $this->_mapper,
                $this->_cache,
                $this->_acl
         );
    }
    protected function _getCache($name = 'default')
    {
        $fc = \Zend_Controller_Front::getInstance();
        $cache = $fc->getParam('bootstrap')
                    ->getResource('cachemanager')
                    ->getCache($name);
        return $cache;
    }
    public function testGetFormReturnsValidator()
    {
        $this->assertTrue($this->_concreteServiceAbstract->getForm() instanceof \Zend_Form);
    }
    /**
     * @expectedException \Net\SylvainBoucher\Services\Exception\InvalidArgumentException
     */
    public function testGetFormThrowExceptionIfNotZendForm()
    {
        $this->_concreteServiceAbstract = new ConcreteServiceAbstract(
                'ConcreteEntity',
                'resource',
                new Zend_Validate(),
                $this->_mapper,
                $this->_cache,
                $this->_acl
         );
        $form = $this->_concreteServiceAbstract->getForm();
    }
    public function testGetResourceid()
    {
        $this->assertSame($this->_concreteServiceAbstract->getResourceId(), 'resource');
    }
    /**
     * @expectedException \Net\SylvainBoucher\Services\Exception\AccessDeniedException
     */
    public function testAccessDeniedOndelete()
    {
        $this->_acl->deny();
        $affectedRows = $this->_concreteServiceAbstract->delete(4);
    }
    public function testFindReturnsEntityObject()
    {
        $this->_mapper->expects($this->once())
                      ->method('find')
                      ->will($this->returnValue(new ConcreteEntity()));
        $entity = $this->_concreteServiceAbstract->find(4);
        $this->assertTrue($entity instanceof \Net\SylvainBoucher\Models\EntityInterface);
    }
    public function testFindCacheDisabled()
    {
        $this->_concreteServiceAbstract->setCacheEnabled(false);
        $this->_mapper->expects($this->once())
                      ->method('find')
                      ->will($this->returnValue(new ConcreteEntity()));
        $entity = $this->_concreteServiceAbstract->find(4);
        $this->assertTrue($entity instanceof \Net\SylvainBoucher\Models\EntityInterface);
    }
    /**
     * @expectedException \Net\SylvainBoucher\Services\Exception\AccessDeniedException
     */
    public function testAccessDeniedOnFind()
    {
        $this->_acl->deny();
        $this->_concreteServiceAbstract->find(4);
    }
    public function testDelete()
    {
        $this->_mapper->expects($this->once())
                      ->method('delete')
                      ->with($this->equalTo(3))
                      ->will($this->returnValue(1));
        $affectedRows = $this->_concreteServiceAbstract->delete(3);
        $this->assertTrue($affectedRows === 1);
    }
    public function testSave()
    {
        $data = array('id' => 3, 'foo' => 'bar');
        $stub = new ConcreteEntityAbstract();
        $this->_mapper->expects($this->once())
                      ->method('save')
                      ->will($this->returnValue($stub->fromArray($data)));
        $this->_concreteServiceAbstract->setValidator(new \Zend_Validate());
        $entity = $this->_concreteServiceAbstract->save($data);
        $this->assertTrue($entity instanceof \Net\SylvainBoucher\Models\EntityInterface);
    }
    public function testSaveWithZendForm()
    {
        $data = array('id' => 3, 'foo' => 'bar');
        $stub = new ConcreteEntityAbstract();
        $this->_mapper->expects($this->once())
                      ->method('save')
                      ->will($this->returnValue($stub->fromArray($data)));
        $form = new \Zend_form();
        $form->addElement('hidden', 'id', 3);
        $this->_concreteServiceAbstract->setValidator($form);
        $entity = $this->_concreteServiceAbstract->save($data);
        $this->assertTrue($entity instanceof \Net\SylvainBoucher\Models\EntityInterface);
    }
    public function testUpdate()
    {
        $data = array('id' => 3, 'foo' => 'bar');
        $stub = new ConcreteEntityAbstract();
        $this->_mapper->expects($this->once())
                      ->method('save')
                      ->will($this->returnValue($stub->fromArray($data)));
        $this->_concreteServiceAbstract->setValidator(new \Zend_Validate());
        $entity = $this->_concreteServiceAbstract->update($data);
        $this->assertTrue($entity instanceof \Net\SylvainBoucher\Models\EntityInterface);
    }
    /**
     * @expectedException \Net\SylvainBoucher\Services\Exception\BadMethodCallException
     */
    public function testUpdateThrowExceptionWhenIdNotSet()
    {
        $data = array('foo' => 'bar');
        $entity = $this->_concreteServiceAbstract->update($data);
        $this->assertTrue($entity instanceof \Net\SylvainBoucher\Models\EntityInterface);
    }
    public function testFetchAll()
    {
        $this->_mapper->expects($this->once())
                      ->method('fetchAll')
                      ->will($this->returnValue(new \Zend_Paginator(new \Zend_Paginator_Adapter_Null(0))));
        $paginator = $this->_concreteServiceAbstract->fetchAll();
        $this->assertTrue($paginator instanceof \Zend_Paginator);
    }
    public function testFetchAllWithCacheDisabled()
    {
        $this->_concreteServiceAbstract->setCacheEnabled(false);
        $this->_mapper->expects($this->once())
                      ->method('fetchAll')
                      ->will($this->returnValue(new \Zend_Paginator(new \Zend_Paginator_Adapter_Null(0))));
        $paginator = $this->_concreteServiceAbstract->fetchAll();
        $this->assertTrue($paginator instanceof \Zend_Paginator);
    }
    public function testCacheEnabled()
    {
        $this->_concreteServiceAbstract->setCacheEnabled(false);
        $this->assertFalse($this->_concreteServiceAbstract->cacheEnabled());
    }
    public function testAclNotRequiredInConstruct()
    {
        $concreteServiceAbstract = new ConcreteServiceAbstract(
                'ConcreteEntity',
                'resource',
                new Zend_Validate(),
                $this->_mapper,
                $this->_cache
         );
        $acl = $concreteServiceAbstract->getAcl();
        $this->assertTrue($acl instanceof \Zend_Acl);

    }
    /**
     * @expectedException \Net\SylvainBoucher\Services\Exception\BadMethodCallException
     */
    public function testSetAclThrowExceptionIfNoParamAndNotInRegistry()
    {
         \Zend_Registry::set('Acl', null);
        $concreteServiceAbstract = new ConcreteServiceAbstract(
                'ConcreteEntity',
                'resource',
                new Zend_Validate(),
                $this->_mapper,
                $this->_cache,
                null
         );
        $concreteServiceAbstract->getAcl();
    }
    public function testGetValidatorReturnsInstanceOfValidator()
    {
        $this->assertTrue($this->_concreteServiceAbstract->getValidator() instanceof \Zend_Validate_Interface);
    }
    public function testGetSearchIndex()
    {
        $this->assertTrue($this->_concreteServiceAbstract->getSearchIndex() instanceof \Zend_Search_Lucene_Proxy);
    }
    public function testGetSearchIndexCreate()
    {
        $fc = \Zend_Controller_Front::getInstance();
        $options = $fc->getParam('bootstrap')->getOption('lucene');
        $path = $options['basepath'];
        exec('rm ' . $path . '/*');
        $this->assertTrue($this->_concreteServiceAbstract->getSearchIndex() instanceof \Zend_Search_Lucene_Proxy);
    }
    public function testDeleteindexDocument()
    {
        $index = $this->_concreteServiceAbstract->getSearchIndex();
        $doc = new \Zend_Search_Lucene_Document();
        $doc->addField(\Zend_Search_Lucene_Field::Keyword('key', 'NetSylvainBoucherServicesServiceAbstract3'));
        $index->addDocument($doc);
        $hits = $index->find('key: NetSylvainBoucherServicesServiceAbstract3');
        $this->assertTrue(1 === count($hits));
        $this->testDelete();
        $hits = $index->find('key: NetSylvainBoucherServicesServiceAbstract3');
        $this->assertTrue(0 === count($hits));
    }
}
class ConcreteServiceAbstract extends \Net\SylvainBoucher\Services\ServiceAbstract
{

}