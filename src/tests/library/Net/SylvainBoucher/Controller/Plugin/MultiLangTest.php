<?php
class Net_SylvainBoucher_Controller_Plugin_MultiLangTest extends PHPUnit_Framework_TestCase
{
    public function testSetDefaultTranslator()
    {
        $multiLang = new Net\SylvainBoucher\Controller\Plugin\MultiLang();
        $translator = \Zend_Registry::getInstance()->Zend_Translate;
        $request = new Zend_Controller_Request_Http();
        $multiLang->routeStartup($request);
        $this->assertSame($translator,\Zend_Controller_Router_Route::getDefaultTranslator());
    }
    public function testSetLocale()
    {
        $multiLang = new Net\SylvainBoucher\Controller\Plugin\MultiLang();
        $translator = \Zend_Registry::getInstance()->Zend_Translate;
        $request = new Zend_Controller_Request_Http();
        $request->setParam('lang', 'es');
        $multiLang->routeShutdown($request);
        $this->assertSame(\Zend_Registry::getInstance()->Zend_Locale->getLanguage(), 'es');
    }
    public function testSetRandomLangParamResetToNull()
    {
        $multiLang = new Net\SylvainBoucher\Controller\Plugin\MultiLang();
        $translator = \Zend_Registry::getInstance()->Zend_Translate;
        $request = new Zend_Controller_Request_Http();
        $request->setParam('lang', 'asdf');
        $multiLang->routeShutdown($request);
        $this->assertSame($request->getParam('lang'), null);
    }
}