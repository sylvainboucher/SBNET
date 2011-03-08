<?php
class Net_SylvainBoucher_Services_UserContainerTest extends PHPUnit_Framework_TestCase
{
    protected $_userContainer;
    public function setUp()
    {
        $this->_userContainer = new \Net\SylvainBoucher\Services\UserContainer();
    }
    public function testGetUserServiceReturnsUser()
    {
        $this->assertTrue($this->_userContainer->getService('user') instanceof  \Net\SylvainBoucher\Services\User);
    }

}