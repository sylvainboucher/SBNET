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
 * Paginator adapter
 *
 * @category   Net\SylvainBoucher
 * @package    Paginator
 * @copyright  Copyright (c) 2011 SylvainBoucher
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id:$
 * @since      File available since Release 0.4
 */

/**
 * @namespace
 */
namespace Net\SylvainBoucher\Paginator;

/**
 * Paginator adapter
 *
 * @category   Net\SylvainBoucher
 * @package    Paginator
 * @uses Zend_Db_Select
 * @copyright  Copyright (c) 2011 SylvainBoucher
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id:$
 * @since      File available since Release 0.4
 *
 */
class PaginatorAdapter extends \Zend_Paginator_Adapter_DbSelect
{
    /**
     * The name of the entity class
     * @var string
     */
    protected $_entityClass;

    /**
     * Constructor
     * @param Zend_Db_Select $select
     * @param string $entityClass
     */
    public function __construct(\Zend_Db_Select $select, $entityClass)
    {
        $this->_select = $select;
        $this->_entityClass = $entityClass;
    }
    /**
     * returns a collection object for a page
     * @param int $offset
     * @param int $itemCountPerPage
     */
    public function getItems($offset, $itemCountPerPage)
    {
        $this->_select->limit($itemCountPerPage, $offset);

        $array = $this->_select->query()->fetchAll();

        $collection = new \Net\SylvainBoucher\Models\Collection();
        foreach ($array as $value) {
            $className = $this->_entityClass;
            $entity = new $className();
            $entity->fromArray($value);
            $collection->addEntity($entity);
        }
        return $collection;
    }
}