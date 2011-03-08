<?php
class Net_SylvainBoucher_Models_UserTest extends PHPUnit_Framework_TestCase
{
    protected $_user;
    public function setUp()
    {
        $this->_user = new \Net\SylvainBoucher\Models\User();
    }
    public function testGetRoleIdDefault()
    {
        $this->assertSame('guest', $this->_user->getRoleId());
    }
}
