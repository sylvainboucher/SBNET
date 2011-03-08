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
 * Create the languageMenu
 *
 * @category   Application\Layouts
 * @package    Helpers
 * @copyright  Copyright (c) 2011 SylvainBoucher
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id:$
 * @since      File available since Release 0.4
 */


/**
 * Create the languageMenu
 *
 * @uses \Zend_Controller_Front
 *
 * @category   Application\Layouts
 * @package    Helpers
 * @copyright  Copyright (c) 2011 SylvainBoucher
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id:$
 * @since      File available since Release 0.4
 */
class Zend_View_Helper_LanguageMenu extends Zend_View_Helper_Abstract
{
    /**
     * Options
     * @var array
     */
    protected $_options;

    /**
     * Create the language menu
     * @return string
     */
    public function languageMenu()
    {
        $languages =  array_keys($this->_getLanguages());
        $defaultLanguage = $this->_getDefaultLanguage();
        $html = '';
        $html .= "<ul id='lang'>";
        foreach ($languages as $language) {
            if ($language === $defaultLanguage) {
                $html .= "<li><a href='{$this->view->url(array("lang" => null), 'defaultRoute')}'>$language</a></li>";
            } else {
                $html .= "<li><a href='{$this->view->url(array("lang" => "$language"), 'default')}'>$language</a></li>";
            }
        }
        $html .= "</ul>";
        return $html;
    }

    /*
     * Return the app languages
     * @return array
     */
    protected function _getlanguages()
    {
        $options = $this->_getOptions();
        $languages = $options['languages'];
        return $languages;
    }

    /*
     * Return the default language
     * @return string
     */
    protected function _getDefaultLanguage()
    {
        $options = $this->_getOptions();
        $default =  $options['resources']['locale']['default'];
        $languages = $this->_getlanguages();
        $key = array_search($default, $languages);
        return $key;
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