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
 * Base class for entities
 *
 * @category   Net\SylvainBoucher
 * @package    Models
 * @copyright  Copyright (c) 2011 SylvainBoucher
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id:$
 * @since      File available since Release 0.4
 */

/**
 * @namespace
 */
namespace Net\SylvainBoucher\Models;

/**
 * Base class for entities
 *
 * @category   Net\SylvainBoucher
 * @package    Models
 * @copyright  Copyright (c) 2011 SylvainBoucher
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id:$
 * @since      File available since Release 0.4
 *
 */
abstract class EntityAbstract implements EntityInterface
{
    /**
     * Holds the entity data
     * @var array
     */
    protected $_data = array(
        'id' => null
    );

    /**
     *  get the data array
     * @return array
     */
    public function toArray()
    {
        return $this->_data;
    }

    /**
     *  Populate the object
     * @param array $data
     * @return EntityAbstract
     */
    public function fromArray(array $data)
    {
        foreach ($data as $name => $value) {
            $this->{$name} = $value;
        }
        return $this;
    }

    /**
     * Return the entity id
     * 
     * @throws \Net\SylvainBoucher\Models\OutOfBoundException
     * @return integer the entity id
     */
    public function getId()
    {
        if (!array_key_exists('id', $this->_data)) {
            throw new Exception\OutOfBoundsException('The id key must be set
                in the data array');
        }
        return (int) $this->_data['id'];
    }

    /**
     *
     * @param integer $id
     * @return EntityAbstract
     */
    public function setId($id)
    {
        $this->_data['id'] = (int) $id;
        return $this;
    }

    /**
     *
     * @return the number of properties
     */
    public function count()
    {
        $count = 0;
        foreach ($this->_data as $value) {
            if (!empty($value)) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * Check if a value is set
     * @param string $name
     * @return mixed
     */
    public function __isset($name)
    {
        return isset($this->_data[$name]);
    }

    /**
     *  unset a value
     * @param string $name
     * @return EntityAbstract
     */
    public function __unset($name)
    {
        if (isset($this->_data[$name])) {
            unset($this->_data[$name]);
        }
        return $this;
    }

    /**
     *
     * @param string $name
     * @param mixed $value
     * @return EntityAbstract
     * @throws \Net\SylvainBoucher\Models\OutOfBoundException
     */
    public function __set($name, $value)
    {
        if (!array_key_exists($name, $this->_data)) {
            throw new Exception\OutOfBoundsException('Invalid key provided');
        }
        $this->_data[$name] = $value;
        return $this;
    }

    /**
     *
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->_data)) {
            return $this->_data[$name];
        }
    }
}