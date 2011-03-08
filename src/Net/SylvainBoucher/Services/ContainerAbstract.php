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
 * Container Abstract
 *
 * @category   Net\SylvainBoucher
 * @package    Services
 * @copyright  Copyright (c) 2011 SylvainBoucher
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id:$
 * @since      File available since Release 0.4
 */

/**
 * @namespace
 */
namespace Net\SylvainBoucher\Services;

/**
 * Container Abstract
 *
 * @category   Net\SylvainBoucher
 * @package    Services
 * @copyright  Copyright (c) 2011 SylvainBoucher
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id:$
 * @since      File available since Release 0.4
 *
 */
abstract class ContainerAbstract implements ContainerInterface
{
    /**
     *
     * @var array
     */
    protected $_services;

    /**
     *  Return an instance of Service object
     * @param string $name
     * @return \Net\SylvainBoucher\Services\ServiceInterface
     */
    public function getService($name)
    {
        if (!isset($this->_services[$name])) {
            $this->addService($name);
        }       
        return $this->_services[$name];
    }

    /**
     * Check if the service container contains the service in memory
     * @param string $name
     * @return bool
     */
    public function hasService($name)
    {
        if (isset($this->_services[$name])) {
            return true;
        }
        return false;
    }

    /**
     *  Return the collection of services attached to the container
     * @return array
     */
    public function getServices()
    {
        return $this->_services;
    }

    /**
     * add a service to the container
     * @param string $name
     * @return ContainerAbstract
     * @throw \Net\Sylvainboucher\Services\Exception\BadMethodCallException
     */
    public function addService($name)
    {
        $methods = get_class_methods($this);
        $method = $name . 'Service';
        if(in_array($method, $methods)) {
            $this->_services[$name] = $this->$method();
            return $this;
        }
        throw new Exception\BadMethodCallException(sprintf('Method "%s" does not exist.', $method));
    }

    /**
     * Return the cache object
     * @param string $name
     * @return \Zend_Cache_Core
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