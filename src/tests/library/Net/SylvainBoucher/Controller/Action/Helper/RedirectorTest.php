<?php
require_once 'Net/SylvainBoucher/Controller/Action/Helper/Redirector.php';
class Net_SylvainBoucher_Controller_Action_Helper_RedirectorTest extends PHPUnit_Framework_TestCase
{
    public function testDirectMethod()
    {
        $redirector = new \Net_SylvainBoucher_Controller_Action_Helper_Redirector();
        $response = new \Zend_Controller_Response_Http();
        \Zend_Controller_Front::getInstance()->setResponse($response);
        $response->headersSentThrowsException = false;
        $redirector->setExit(false);
        $redirector->direct('index');
        $this->assertTrue($response->isRedirect());
    }
    public function testDirectMethodWithParams()
    {
        $redirector = new \Net_SylvainBoucher_Controller_Action_Helper_Redirector();
        $response = new \Zend_Controller_Response_Http();
        \Zend_Controller_Front::getInstance()->setResponse($response);
        $response->headersSentThrowsException = false;
        $redirector->setExit(false);
        $redirector->direct('index', null, null, array('id' => 4));
        $this->assertSame($redirector->getRedirectUrl(), '/index/index/id/4');
    }
    public function testDirectMethodWithLangParam()
    {
        $request = \Zend_Controller_Front::getInstance()->getRequest();
        $request->setParam('lang', 'fr');
        $redirector = new \Net_SylvainBoucher_Controller_Action_Helper_Redirector();
        $response = new \Zend_Controller_Response_Http();
        \Zend_Controller_Front::getInstance()->setResponse($response);
        $response->headersSentThrowsException = false;
        $redirector->setExit(false);
        $redirector->direct('index');
        $this->assertSame($redirector->getRedirectUrl(), '/fr/');
    }
}