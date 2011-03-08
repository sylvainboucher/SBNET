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
 * Service interface
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
 * Service interface
 *
 * @category   Net\SylvainBoucher
 * @package    Services
 * @copyright  Copyright (c) 2011 SylvainBoucher
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id:$
 * @since      File available since Release 0.4
 *
 */
interface ServiceInterface
    extends \Zend_Acl_Resource_Interface
{
    public function __construct(
        $entityClass,
        $ressourceId,
        \Zend_Validate_Interface $validator,
        \Net\SylvainBoucher\Models\Mappers\MapperInterface $mapper,
        \Zend_Cache_Core $cache,
        \Zend_Acl $acl = null
    );
    public function fetchAll($offset = null, $limit = null);
    public function find($key);
    public function save(array $data);
    public function update(array $data);
    public function delete($id);
    public function getForm();
    public function setCacheEnabled($enable);
    public function getAcl();
    public function setAcl(\Zend_Acl $acl = null);
    public function getValidator();
    public function setValidator(\Zend_Validate_Interface $validator);
    public function getSearchIndex();
}
