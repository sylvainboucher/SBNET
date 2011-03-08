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
 * Plugin to set locale/translator based on :lang param in router
 *
 * @category   Net\SylvainBoucher
 * @package    Controller
 * @subpackage Plugin
 * @copyright  Copyright (c) 2011 SylvainBoucher
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id:$
 * @since      File available since Release 0.3
 */

/**
 * @namespace
 */
namespace Net\SylvainBoucher\Controller\Plugin;

/**
 * Plugin to set locale/translator based on :lang param in router
 *
 * @uses \Zend_Controller_Request_Abstract
 * @uses \Zend_Controller_Router_Route
 * @uses \Zend_Registry
 * @uses \Zend_Translate
 * @uses \Zend_Locale
 *
 * @category   Net\SylvainBoucher
 * @package    Controller
 * @subpackage Plugin
 * @copyright  Copyright (c) 2011 SylvainBoucher
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id:$
 * @since      File available since Release 0.3
 */
class MultiLang extends \Zend_Controller_Plugin_Abstract
{
    /**
     *
     * @var Zend_Translate
     */
    protected $_translator;

    /**
     *
     * @var array
     */
    protected $_options;

    /**
     *  Languages set in config
     * @var array
     */
    protected $_languages;

    /**
     * Default locale language
     * set in config
     * @var string
     */
    protected $_defaultLanguage;

    /**
     * Set the default translator to Router
     * @param \Zend_Controller_Request_Abstract $request
     */
    public function routeStartup(\Zend_Controller_Request_Abstract $request)
    {
        \Zend_Controller_Router_Route::setDefaultTranslator($this->_getTranslator());
    }    

    /**
     * Get the language from the request and set the Locale and Translator
     * accordingly.
     * @param \Zend_Controller_Request_Abstract $request
     */
    public function routeShutdown(\Zend_Controller_Request_Abstract $request)
    {
        $this->_filterLang($request);
        $defaultLanguage = $this->_getDefaultLanguage();
        $language =  $request->getParam("lang", $defaultLanguage);
        $languages = $this->_getlanguages();
        $locale = new \Zend_Locale($languages[$language]);
        \Zend_Registry::getInstance()->Zend_Locale->setLocale($locale);
        $translate = $this->_getTranslator();
        $translate->getAdapter()->setLocale($locale);
    }

    /**
     * return the default language
     */
    protected function _getDefaultLanguage()
    {
        if (null === $this->_defaultLanguage) {
            $options = $this->_getOptions();
            $defaultLocale = $options['resources']['locale']['default'];
            $this->_defaultLanguage = array_search($defaultLocale, $options['languages']);
        }
        return $this->_defaultLanguage;
    }

    /*
     * Return the app languages
     * @return array
     */
    protected function _getlanguages()
    {
        if (null === $this->_languages) {
            $options = $this->_getOptions();
            $this->_languages = $options['languages'];
        }
        return $this->_languages;
    }

    /**
     * Set the translator property
     * @return Zend_Translate
     */
    protected function _getTranslator()
    {
        if(null === $this->_translator) {
            $this->_translator = \Zend_Registry::getInstance()->Zend_Translate;
        }
        return $this->_translator;
    }
 
    /*
     * Sanitize the request lang param
     * Remove default language to use default module route
     */    
    protected function _filterLang($request)
    {
        $languages = $this->_getlanguages();
        $default = $this->_getDefaultLanguage();
        if ($default !== false && isset($languages[$default])) {
            unset($languages[$default]);
        }
        $lang = $request->getParam('lang');
        if (isset($lang) && !array_key_exists($lang, $languages)) {
            $request->setParam('lang', null);
        }
    } 

    /**
     *  Return the options
     * @return array
     */
    protected function _getOptions()
    {
        if (null === $this->_options) {
            $fc = \Zend_Controller_Front::getInstance();
            $bootstrap = $fc->getParam('bootstrap');
            $this->_options = $bootstrap->getOptions();
        }
        return $this->_options;
    }
}
