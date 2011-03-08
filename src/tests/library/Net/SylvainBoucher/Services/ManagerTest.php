<?php
class Net_SylvainBoucher_Services_ManagerTest extends PHPUnit_Framework_TestCase
{
    protected $_manager;
    public function setUp()
    {
        $this->_manager = new \Net\SylvainBoucher\Services\Manager();
    }
    public function testAddGetServiceContainer()
    {
        $className = 'Net\SylvainBoucher\Services\User';
        $this->_manager->addServiceContainer($className);
        $this->assertTrue($this->_manager->getServiceContainer($className) instanceof \Net\SylvainBoucher\Services\User);
    }
    /**
     * @expectedException \Net\SylvainBoucher\Services\Exception\BadMethodCallException
     */
    public function testaddContainerThrowExceptionIfInvalidArgument()
    {
        $className = array();
        $this->_manager->addServiceContainer($className);
    }
    /**
     * @expectedException \Net\SylvainBoucher\Services\Exception\BadMethodCallException
     */
    public function testGetServiceContainerThrowExceptionIfClassNotSet()
    {
        $this->_manager->getServiceContainer('User');
    }
    public function testRemoveServiceContainer()
    {
        $className = 'Net\SylvainBoucher\Services\User';
        $this->_manager->addServiceContainer($className);
        $this->assertTrue($this->_manager->hasServiceContainer($className));
        $this->_manager->removeServiceContainer($className);
        $this->assertFalse($this->_manager->hasServiceContainer($className));
    }
    public function testSetClearServiceContainer()
    {
        $serviceContainers = array('User', 'User2');
        $this->_manager->setServiceContainers($serviceContainers);
        foreach ($serviceContainers as $container) {
            $this->assertTrue($this->_manager->hasServiceContainer($container));
        }
        $this->_manager->clearServiceContainers();
        foreach ($serviceContainers as $container) {
            $this->assertFalse($this->_manager->hasServiceContainer($container));
        }
    }
}