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
 * Overwrite the Zend action helper redirector direct method
 * to set default route and lang param.
 *
 * @category   Net\SylvainBoucher
 * @package    Controller
 * @subpackage Action
 * @copyright  Copyright (c) 2011 SylvainBoucher
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id:$
 * @since      File available since Release 0.4
 */

/**
 * Overwrite the Zend action helper redirector direct method
 * to set default route and lang param.
 *
 * @uses \Zend_Controller_Front
 *
 * @category   Net\SylvainBoucher
 * @package    Controller
 * @subpackage Action
 * @copyright  Copyright (c) 2011 SylvainBoucher
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id:$
 * @since      File available since Release 0.4
 */
class Net_SylvainBoucher_Controller_Action_Helper_Redirector extends \Zend_Controller_Action_Helper_Redirector
{

    /**
     * direct(): Perform helper when called as
     * $this->_helper->redirector($action, $controller, $module, $params)
     * Set default route and call goToRoute()
     *
     * @param  string $action
     * @param  string $controller
     * @param  string $module
     * @param  array  $params
     * @return void
     */
    public function direct($action, $controller = null, $module = null, array $params = array())
    {
        $urlOptions['action'] = $action;
        $urlOptions['controller'] = $controller;
        $urlOptions['module'] = $module;
        foreach($params as $key => $value) {
            $urlOptions[$key] = $value;
        }
        $request = \Zend_Controller_Front::getInstance()->getRequest();
        $lang = $request->getParam('lang');
        if(isset($lang)) {
            $name = 'default';
            $urlOptions['lang'] = $lang;
        } else {
            $name = 'defaultRoute';
        }
        $this->gotoRoute($urlOptions, $name);
    }
}