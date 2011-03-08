<?php
class Net_SylvainBoucher_Controller_Action_Helper_UrlTest extends PHPUnit_Framework_TestCase
{
    public function testIfIssetLangUseDefault()
    {
        $urlHelper = new Net\SylvainBoucher\Controller\Action\Helper\Url();
        $request = \Zend_Controller_Front::getInstance()->getRequest();
        $request->setParam('lang', 'fr');
        $this->assertSame($urlHelper->url(), '/fr/');
    }
    public function testIfNotSetLangUseDefaultRoute()
    {
        $urlHelper = new Net\SylvainBoucher\Controller\Action\Helper\Url();
        $request = \Zend_Controller_Front::getInstance()->getRequest();
        $request->setParam('lang', null);
        $this->assertSame($urlHelper->url(), '/');
    }
}