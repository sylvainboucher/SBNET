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
 * Bootstrap
 *
 * @category   application
 * @copyright  Copyright (c) 2011 SylvainBoucher
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id:$
 */

/**
 * Bootstrap
 *
 * @category   application
 * @copyright  Copyright (c) 2011 SylvainBoucher
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id:$
 *
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
     * Add pluginloader file cache
     */
    protected function _initPluginLoaderCache()
    {
        $options = $this->getOptions();
        if ($options['enablePluginLoaderCache']) {
            $classFileIncCache = APPLICATION_PATH . '/../data/cache/pluginLoaderCache.php';

            if (file_exists($classFileIncCache)) {
                include_once $classFileIncCache;
            }
            \Zend_Loader_PluginLoader::setIncludeFileCache($classFileIncCache);
        }
    }

    /**
     * Initialize view helpers
     */
    protected function _initViewHelpers()
    {
        $this->bootstrap('View');
        $view = $this->getResource('View');
        $options = $this->getOptions();
        
        $view->addHelperPath('Net/SylvainBoucher/View/Helper', 'Net_SylvainBoucher_View_Helper');
        $view->addHelperPath(APPLICATION_PATH . '/layouts/helpers', 'Zend_View_Helper');

        $view->doctype('XHTML1_STRICT');
        $view->headTitle()->setSeparator($options['layouthelper']['separator'])
                          ->append($options['layouthelper']['title']);
        $view->headMeta()->appendHttpEquiv('Content-Type',
                                          'text/html; charset=utf-8');
        $view->headMeta()->appendName('keywords', $options['layouthelper']['keywords']);
        $view->headMeta()->appendName('description', $options['layouthelper']['description']);
    }

    /**
     *  Initialize the Service Manager and returns it
     * @return \Net\SylvainBoucher\Services\Manager
     */
    protected function _initServiceManager()
    {
        $serviceManager = new \Net\SylvainBoucher\Services\Manager();
        \Zend_Registry::set('ServiceManager', $serviceManager);
        return $serviceManager;
    }

    /**
     * Initialize the Acl object and returns it
     * @return \Application\Configs\Acl
     */
    protected function _initAcl()
    {
        require_once APPLICATION_PATH . '/configs/Acl.php';
        $acl = new \Application\Configs\Acl();
        \Zend_Registry::set('Acl', $acl);
        return $acl;
    }

    /**
     * Set cache object to Locale
     */
    protected function _initLocaleCache()
    {
        $this->_bootstrap('Locale');
        $this->_bootstrap('CacheManager');
        $locale = $this->getResource('Locale');
        $cacheManager = $this->getResource('CacheManager');
        $locale->setCache($cacheManager->getCache('default'));
    }    
}

