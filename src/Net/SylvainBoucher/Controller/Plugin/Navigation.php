<?php
/**
 *
 * Copyright 2011 Sylvain Boucher
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * Plugin to initialize site navigation
 *
 * @category   Net\SylvainBoucher
 * @package    Controller
 * @subpackage Plugin
 * @copyright  Copyright (c) 2011 SylvainBoucher
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id:$
 * @since      File available since Release 0.4
 */

/**
 * @namespace
 */
namespace Net\SylvainBoucher\Controller\Plugin;

/**
 * Plugin to initialize site navigation
 *
 * @uses \Zend_Controller_Request_Abstract
 * @uses \Zend_Navigation
 * @uses \Zend_Registry
 *
 * @category   Net\SylvainBoucher
 * @package    Controller
 * @subpackage Plugin
 * @copyright  Copyright (c) 2011 SylvainBoucher
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id:$
 * @since      File available since Release 0.4
 */
class Navigation extends \Zend_Controller_Plugin_Abstract
{
    /**
     * Initialize the Navigation container and set it in the registry.
     * @param \Zend_Controller_Request_Abstract $request
     */
    public function preDispatch(\Zend_Controller_Request_Abstract $request)
    {
        \Zend_Navigation_Page_Mvc::setUrlHelper(
            new \Net\SylvainBoucher\Controller\Action\Helper\Url()
        );
        $container = new \Zend_Navigation($this->_getPages());
        \Zend_View_Helper_Navigation_HelperAbstract::setDefaultAcl($this->_getAcl());
        \Zend_View_Helper_Navigation_HelperAbstract::setDefaultRole($this->_getRole());
        \Zend_Registry::set('Zend_Navigation', $container);
    }

    /**
     * Return the Acl instance
     * @return \Zend_Acl
     */
    protected function _getAcl()
    {
        return \Zend_Registry::getInstance()->Acl;
    }

    /**
     *  Return the current role
     * @return string
     */
    protected function _getRole()
    {
        $serviceManager = \Zend_Registry::getInstance()->ServiceManager;
        $userContainer = $serviceManager
            ->getServiceContainer('Net\SylvainBoucher\Services\UserContainer');
        $userService = $userContainer->getService('user');
        return $userService->getCurrentUser()->getRoleId();

    }

    /**
     * Returns an array of page configuration for navigation.
     * @param string $route
     * @param string $params
     * @uses \Application\Configs\Navigation
     * @return array
     */
    protected function _getPages()
    {
        require_once APPLICATION_PATH . '/configs/Navigation.php';
        $navigation = new \Application\Configs\Navigation();
        return $navigation->getPages();
    }

}
