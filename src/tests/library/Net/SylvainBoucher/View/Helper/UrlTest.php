<?php
require_once 'Net/SylvainBoucher/View/Helper/Url.php';
class Net_SylvainBoucher_View_Helper_UrlTest extends PHPUnit_Framework_TestCase
{
    public function testurlMethodWithoutParams()
    {
        $request = \Zend_Controller_Front::getInstance()->getRequest();
        $request->clearParams();
        $urlHelper = new Net_SylvainBoucher_View_Helper_Url();
        $this->assertSame($urlHelper->url(), '/');
    }
}
