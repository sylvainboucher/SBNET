<?php
class Net_SylvainBoucher_Services_ContainerAbstractTest extends PHPUnit_Framework_TestCase
{
    public function testGetCache()
    {
        $container = new ConcreteContainerAbstract();
        $this->assertTrue($container->getCache() instanceof  Zend_Cache_Core);
    }
    public function testHasService()
    {
        $container = new ConcreteContainerAbstract();
        $container->addService('user');
        $this->assertTrue($container->hasService('user'));
    }
    public function testHasServiceReturnFalseIfNotSet()
    {
        $container = new ConcreteContainerAbstract();
        $this->assertFalse($container->hasService('foo'));
    }
    public function testGetService()
    {
        $container = new ConcreteContainerAbstract();
        $container->addService('user');
        $this->assertTrue($container->getService('user') instanceof Net\SylvainBoucher\Services\User);
    }
    public function testGetServices()
    {
        $container = new ConcreteContainerAbstract();
        $container->addService('user');
        $services = $container->getServices();
        $this->assertTrue(is_array($services));
        $this->assertTrue(isset($services['user']));
        $this->assertTrue($services['user'] instanceof Net\SylvainBoucher\Services\User);
    }
    /**
     * @expectedException \Net\Sylvainboucher\Services\Exception\BadMethodCallException
     */
    public function testAddServiceThrowExceptionIfMethodNotExist()
    {
        $container = new ConcreteContainerAbstract();
        $container->addService('bar');
    }
}
class ConcreteContainerAbstract extends \Net\SylvainBoucher\Services\ContainerAbstract
{
    public function getCache()
    {
        return $this->_getCache();
    }
    public function userService()
    {
        return new Net\SylvainBoucher\Services\User();
    }
}