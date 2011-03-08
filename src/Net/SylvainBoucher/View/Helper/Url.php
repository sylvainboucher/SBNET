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
 * Overwrite the Zend view helper to set default route and lang param
 *
 * @category   Net\SylvainBoucher
 * @package    View
 * @subpackage Helper
 * @copyright  Copyright (c) 2011 SylvainBoucher
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id:$
 * @since      File available since Release 0.4
 */


/**
 * Overwrite the Zend view helper to set default route and lang param
 *
 * @uses \Zend_Controller_Front
 *
 * @category   Net\SylvainBoucher
 * @package    View
 * @subpackage Helper
 * @copyright  Copyright (c) 2011 SylvainBoucher
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id:$
 * @since      File available since Release 0.4
 */
class Net_SylvainBoucher_View_Helper_Url extends Zend_View_Helper_Abstract
{
    /**
     * Generates a url given the name of a route.
     *
     * @access public
     *
     * @param  array $urlOptions Options passed to the assemble method of the Route object.
     * @param  mixed $name The name of a Route to use. If null it will set the current Route
     * @param  bool $reset Whether or not to reset the route defaults with those provided
     * @return string Url for the link href attribute.
     */
    public function url(array $urlOptions = array(), $name = null, $reset = false, $encode = true)
    {
        $request = \Zend_Controller_Front::getInstance()->getRequest();
        $lang = $request->getParam('lang');
        if(null == $name) {
            $name = isset($lang) ? 'default' : 'defaultRoute';
        }
        $router = \Zend_Controller_Front::getInstance()->getRouter();
        return $router->assemble($urlOptions, $name, $reset, $encode);
    }


}