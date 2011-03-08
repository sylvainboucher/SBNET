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
 * Mapper abstract
 *
 * @category   Net\SylvainBoucher
 * @package    Models
 * @subpackage Mappers
 * @copyright  Copyright (c) 2011 SylvainBoucher
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id:$
 * @since      File available since Release 0.4
 */

/**
 * @namespace
 */
namespace Net\SylvainBoucher\Models\Mappers;

/**
 * mapper abstract
 *
 * @category   Net\SylvainBoucher
 * @package    Models
 * @subpackage Mappers
 * @copyright  Copyright (c) 2011 SylvainBoucher
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id:$
 * @since      File available since Release 0.4
 *
 */
abstract class MapperAbstract implements MapperInterface
{
    /**
     *
     * @var  object gateway
     */
    protected $_gateway;

    /**
     *
     * @var string
     */
    protected $_entityClass;

    /**
     *
     * @param string $entityClass
     * @param  $gateway  object gateway
     */
    public function __construct($entityClass, $gateway)
    {
        $this->_setGateway($gateway);
        $this->_setEntityClass($entityClass);
    }

    /**
     *
     * @param integer $key
     * @param \Net\SylvainBoucher\Models\EntityInterface $entity
     * @return \Net\SylvainBoucher\Models\EntityInterface
     */
    public function find($key, \Net\SylvainBoucher\Models\EntityInterface $entity)
    {
        $select = $this->_getDbTable()->select()->where('id = ?', (int) $key);
        $row = $this->_getDbTable()->fetchRow($select);
        if (null !== $row) {
            return $entity->fromArray($row->toArray());
        }
        return false;
    }

    /**
     *
     * @param integer $offset
     * @param integer $limit
     * @return \Zend_Paginator
     */
    public function fetchAll($offset = 1, $limit = null)
    {
        $select = $this->_getDbTable()->select();
        $paginator = $this->getPaginatorInstance($select);
        $paginator->setCurrentPageNumber($offset);
        if (!empty($limit)) {
            $paginator->setDefaultItemCountPerPage($limit);
        }
        return $paginator;
    }

    /**
     *
     * @param \Net\SylvainBoucher\Models\EntityInterface $entity
     * @return \Net\SylvainBoucher\Models\EntityInterface
     */
    public function save(\Net\SylvainBoucher\Models\EntityInterface $entity)
    {
        if (!$entity->getId()) {
            $id = $this->_getDbTable()->insert($entity->toArray());
            $entity->setId($id);
        } else {
            $where = $this->_getDbTable()
                          ->getAdapter()
                          ->quoteInto('id = ?', (int) $entity->getId());
            $this->_getDbTable()->update($entity->toArray(), $where);
        }
        return $entity;
    }

    /**
     *
     * @param integer $id
     * @return integer
     */
    public function delete($id)
    {
        $where = $this->_getDbTable()->getAdapter()->quoteInto('id = ?', $id);
        return $this->_getDbTable()->delete($where);
    }

    /**
     *
     * @param object $gateway
     * @return MapperAbstract
     */
    protected function _setGateway($gateway)
    {
        $this->_gateway = $gateway;
        return $this;
    }

    /**
     *
     * @return object
     */
    protected function _getGateway()
    {
        return $this->_gateway;
    }

    /**
     *
     * @return \Zend_Db_Table_Abstract
     */
    protected function _getDbTable()
    {
        if (!$this->_gateway instanceof \Zend_Db_Table_Abstract) {
            throw new Exception\InvalidArgumentException('The gateway is not an
                instance of Zend_Db_Table_Abstract');
        }
        return $this->_gateway;
    }

    /**
     *
     * @param string $entityClass
     * @return MapperAbstract
     */
    protected function _setEntityClass($entityClass)
    {
        $this->_entityClass = (string) $entityClass;
        return $this;
    }

    /**
     *
     * @return string
     */
    protected function _getEntityClass()
    {
        return $this->_entityClass;
    }

    /**
     *
     * @return Zend_Paginator
     */
    public function getPaginatorInstance($select)
    {
        $paginator = new \Zend_Paginator(
            new \Net\SylvainBoucher\Paginator\PaginatorAdapter(
                $select,
                $this->_getEntityClass()
            )
        );
        return $paginator;
    }
}