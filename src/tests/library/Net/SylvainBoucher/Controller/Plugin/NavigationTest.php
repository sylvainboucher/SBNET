<?php
class Net_SylvainBoucher_Controller_Plugin_NavigationTest extends PHPUnit_Framework_TestCase
{
    protected $_navigation;
    public function setUp()
    {
        $this->_navigation = new \Net\SylvainBoucher\Controller\Plugin\Navigation();
    }
    public function testPreDispatchSetNavigationInRegistry()
    {
        $request = new \Zend_Controller_Request_Http();
        $this->_navigation->preDispatch($request);
        $this->assertTrue(isset(\Zend_Registry::getInstance()->Zend_Navigation));
    }
}