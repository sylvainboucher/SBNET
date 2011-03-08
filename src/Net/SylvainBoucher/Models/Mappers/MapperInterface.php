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
 * Mapper interface
 *
 * @category   Net\SylvainBoucher
 * @package    Models
 * @subpackage Mappers
 * @copyright  Copyright (c) 2011 SylvainBoucher
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id:$
 * @since      File available since Release 0.1
 */

/**
 * @namespace
 */
namespace Net\SylvainBoucher\Models\Mappers;

/**
 * mapper interface
 *
 * @category   Net\SylvainBoucher
 * @package    Models
 * @subpackage Mappers
 * @copyright  Copyright (c) 2011 SylvainBoucher
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id:$
 * @since      File available since Release 0.1
 *
 */
interface MapperInterface
{
    public function __construct($entityClass, $gateway);
    public function find($key, \Net\SylvainBoucher\Models\EntityInterface $entity);
    public function fetchAll($offset = null, $limit = null);
    public function save(\Net\SylvainBoucher\Models\EntityInterface $entity);
    public function delete($id);
    public function getPaginatorInstance($select);
}
