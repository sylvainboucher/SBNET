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
 * Configuration for navigation
 *
 * @category   application\Configs
 * @package    Navigation
 * @copyright  Copyright (c) 2011 SylvainBoucher
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id:$
 * @since      File available since Release 0.4
 */

/**
 * @namespace
 */
namespace Application\Configs;

/**
 * Configuration for navigation
 *
 * @category   application\Configs
 * @package    Navigation
 * @copyright  Copyright (c) 2011 SylvainBoucher
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id:$
 * @since      File available since Release 0.4
 *
 */
class Navigation
{

    /**
     *  Return array of pages.
     *  if cache is available, it will return it.
     * @return array
     */
    public function getPages()
    {
        $cache = $this->_getCache();
        $pages = $cache->load('NavigationPages');
        if ($pages === false) {
            $pages = $this->_getPages();
            $cache->save($pages, 'NavigationPages');
        }
        return $pages;
    }

    /**
     * Returns an array of page configuration for navigation.
     * @param string $route
     * @param string $params
     * @return array
     */
    protected function _getPages()
    {
        $pages = array(
            array(
                'label' => 'home',
                'route' => 'default',
                'controller' => 'index',
                'action' => 'index',
                'resource' => null,
                'privilege' => null
            )
        );
        return $pages;
    }

    /**
     * Return the cache object
     * @param string $name
     * @return /Zend_Cache_Core
     */
    protected function _getCache($name = 'default')
    {
        $fc = \Zend_Controller_Front::getInstance();
        $cache = $fc->getParam('bootstrap')
                    ->getResource('cachemanager')
                    ->getCache($name);
        return $cache;
    }
}
