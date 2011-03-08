<?php
class Net_SylvainBoucher_Services_UserTest extends PHPUnit_Framework_TestCase
{
    protected $_user;
    public function setUp()
    {
        $this->_user = new \Net\SylvainBoucher\Services\User();
    }
    public function testGetCurrentuserReturnsEntityAbstractInstance()
    {
        $this->assertTrue($this->_user->getCurrentuser() instanceof \Net\SylvainBoucher\Models\EntityAbstract);
    }

}