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
 * User model 
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
 * User model
 *
 * @category   Net\SylvainBoucher
 * @package    Models
 * @copyright  Copyright (c) 2011 SylvainBoucher
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id:$
 * @since      File available since Release 0.4
 *
 */
 class User extends EntityAbstract implements \Zend_Acl_Role_Interface
 {
     /**
      * The user role
      * @var string
      */
	protected $_roleId = 'guest';

    /**
     *  Return the role id
     * @return string
     */
	public function getRoleId()
	{
		return $this->_roleId;
	}
 }