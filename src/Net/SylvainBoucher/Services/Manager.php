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
 * Services Manager
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
 * Services Manager
 *
 * @category   Net\SylvainBoucher
 * @package    Services
 * @copyright  Copyright (c) 2011 SylvainBoucher
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id:$
 * @since      File available since Release 0.4
 *
 */
class Manager implements ManagerInterface
{
    /**
     *
     * @var array containers objects
     */
    protected $_serviceContainers = array();

    /**
     * The constructor
     */
    public function __construct()
    {
        $this->addServiceContainer('Net\SylvainBoucher\Services\UserContainer');
    }

    /**
     *  Add a container
     * @param string $className
     * @return Manager
     */
    public function addServiceContainer($className)
    {
        if (!is_string($className)) {
            throw new Exception\BadMethodCallException('The class name
                must be a string');
        }
        $this->_serviceContainers[$className] = null;
        return $this;
    }

    /**
     *  Remove a container
     * @param string $className
     * @return Manager
     */
    public function removeServiceContainer($className)
    {
        unset($this->_serviceContainers[$className]);
        return $this;
    }

    /**
     *  set containers in batch
     * @param array $serviceContainers
     * @return Manager
     */
    public function setServiceContainers(array $serviceContainers)
    {
        foreach ($serviceContainers as $serviceContainer) {
            $this->addServiceContainer($serviceContainer);
        }
        return $this;
    }

    /**
     * Clear the containers registry
     * @return Manager
     */
    public function clearServiceContainers()
    {
        $this->_serviceContainers = array();
        return $this;
    }


    public function hasServiceContainer($className)
    {
        return array_key_exists($className, $this->_serviceContainers);
    }

    /**
     * Get a service container from the registry
     * @throw Net\SylvainBoucher\Services\Exception\BadMethodCallException
     * @param string $className
     * @return Net\SylvainBoucher\Services\ContainerInterface
     */
    public function getServiceContainer($className)
    {
        if (!$this->hasServiceContainer($className)) {
            throw new Exception\BadMethodCallException('The class name is not set');
        }
        if (null === $this->_serviceContainers[$className]) {
            $this->_serviceContainers[$className] = new $className();
        }
        return $this->_serviceContainers[$className];
    }
}
