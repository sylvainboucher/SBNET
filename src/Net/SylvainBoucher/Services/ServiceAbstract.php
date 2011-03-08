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
 * Services Abstract
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
 * Services Abstract
 *
 * @uses \Zend_Validate
 * @uses \Net\SylvainBoucher\Models\Mapper\MapperInterface
 * @uses \Zend_Cache_Core
 * @uses \Zend_Acl
 * @category   Net\SylvainBoucher
 * @package    Services
 * @copyright  Copyright (c) 2011 SylvainBoucher
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id:$
 * @since      File available since Release 0.4
 *
 */
abstract class ServiceAbstract implements ServiceInterface
{
	
    /**
     * Enable or disable the cache by service instance
     *
     * @var bool
     */
    protected $_cacheEnabled = true;

    /**
     * Unique key representing the service
     * @var string
     */
    protected $_serviceId;
	
	/**
     *  The resource id
     * @var string
     */
	protected $_resourceId;

    /**
     *
     * @var \Zend_Acl
     */
	protected $_acl;

    /**
     *
     * @var \Zend_Validator
     */
	protected $_validator;

    /**
     * The mapper
     * @var \Net\SylvainBoucher\Models\Mappers\MapperInterface
     */
	protected $_mapper;


    /**
     * The entity class name
     * @var string
     */
	protected $_entityClass;

    /**
     * the Zend_Search_Lucene index
     * @var \Zend_Search_Lucene
     */
    protected $_searchIndex;

    /**
     * The constructor
     * @param string $entityClass
     * @param string $resourceId
     * @param \Zend_Validate $validator
     * @param \Net\SylvainBoucher\Models\Mapper\MapperInterface $mapper
     * @param \Zend_Cache_Core $cache
     * @param \Zend_Acl $acl
     */
    public function __construct(
        $entityClass,
        $resourceId,
        \Zend_Validate_Interface $validator,
        \Net\SylvainBoucher\Models\Mappers\MapperInterface $mapper,
        \Zend_Cache_Core $cache,
        \Zend_Acl $acl = null
    ) {
		$this->_setEntityClass($entityClass);
		$this->_setResourceId($resourceId);
		$this->setValidator($validator);
		$this->_setMapper($mapper);
		$this->_setCache($cache);
		$this->setAcl($acl);
	}

    /**
     * Fetch a collection of entities
     * @param integer $offset
     * @param integer $limit
     * @return \Zend_Paginator
     */
    public function fetchAll($offset = null, $limit = null)
	{
        $paginator = null;
		if ($this->_isAllowed(
                $this->_getCurrentUser(),
                $this,
                'read')
        ) {
			$paginator = $this->_getMapper()->fetchAll($offset, $limit);
			if (!$this->_cacheEnabled) {
				$paginator->setCacheEnabled(false);
			} else {
                \Zend_Paginator::setCache($this->_getCache());
            }
		}
		return $paginator;
	}

    /**
     * find an entity
     * @param integer $key
     * @return \Net\SylvainBoucher\Models\EntityInterface
     */
    public function find($key)
	{
        $entity = null;
		if ($this->_isAllowed(
                $this->_getCurrentUser(),
                $this,
                'read')
        ) {
			if ($this->_cacheEnabled === false) {
				return $this->_find($key);
			}
			$cacheId = $this->_getServiceId() . $key;
			$cache = $this->_getCache();
			$entity = $cache->load($cacheId);
			if ($entity === false) {
				$entity = $this->_find($key);
				$cache->save($entity, $cacheId, array($this->_getServiceId()));
			}
		}
        return $entity;
	}

    /**
     * Save the entity
     * @param array $data
     * @return \Net\SylvainBoucher\Models\EntityInterface
     */
    public function save(array $data)
	{
        $entity = null;
		if ($this->_isValid($data) 
			&& $this->_isAllowed(
                    $this->_getCurrentUser(),
                    $this,
                    'create')
		) {
            if ($this->getValidator() instanceof  \Zend_Form) {
                $data = $this->getValidator()->getValues();
            }
			$entity = $this->_save($data);
		}
        if (count($entity)) {
            $this->_updateSearchIndex('save',$entity);
        }
        return $entity;
	}

    /**
     * Update the entity
     * @param array $data
     * @return \Net\SylvainBoucher\Models\EntityInterface
     * @throws \Net\SylvainBoucher\Services\Exception\BadMethodCallException
     */
    public function update(array $data)
	{
        $entity = null;
		if ($this->_isValid($data) 
			&& $this->_isAllowed(
                    $this->_getCurrentUser(),
                    $this,
                    'update')
		) {
            if ($this->getValidator() instanceof  \Zend_Form) {
                $data = $this->getValidator()->getValues();
            }
            if (!isset($data['id']) || empty($data['id'])) {
                throw new Exception\BadMethodCallException(
                        'The Id must be set to perform an update'
                );
            }
			$entity = $this->_save($data);
		}
        if (count($entity)) {
            $this->_updateSearchIndex('update',$entity);
        }
        return $entity;
	}

    /**
     *  Delete the entity
     * @param integer $id
     * @return integer Affected rows
     */
    public function delete($id)
	{
        $affectedRows = 0;
		if ($this->_isAllowed($this->_getCurrentUser(), $this, 'delete')) {
			$affectedRows = $this->_getMapper()->delete((int) $id);
            if ($affectedRows > 0) {
                $this->_clearCache();
                $className = $this->_getEntityClass();
                $entity = new $className();
                $entity->setId($id);
                $this->_updateSearchIndex('delete', $entity);
            }
		}
        return $affectedRows;
	}

    /**
     * Return the Zend Form instance
     * @return Zend_Form
     * @throws \Net\SylvainBoucher\Services\Exception\InvalidArgumentException
     */
    public function getForm()
	{
		if (!$this->_validator instanceof \Zend_Form) {
			throw new Exception\InvalidArgumentException('The validator must be an
				instance of Zend_Form');
		}
		return $this->_validator;
	}

    /**
     * Enable or disable the cache by service instance
     * @param bool $enable
     * @return ServiceAbstract
     */
    public function setCacheEnabled($enable)
	{
        $this->_cacheEnabled = (bool) $enable;
        return $this;	
	}

    /**
     * Returns whether the cache is enabled
     * @return bool
     */
    public function cacheEnabled()
    {
        return $this->_cacheEnabled;
    }

    /**
     *
     * @return string
     */
    public function getResourceId()
	{
		return $this->_resourceId;
	}

    /**
     *  Set the resource id
     * @param string $resourceId
     * @return ServiceAbstract
     */
    protected function _setResourceId($resourceId)
	{
		$this->_resourceId = (string) $resourceId;
		return $this;
	}

    /**
     *  Return the Zend_Acl instance
     * @return Zend_Acl
     */
    public function getAcl()
	{
		return $this->_acl;
	}

    /**
     *
     * @param \Zend_Acl $acl
     * @return ServiceAbstract
     */
    public function setAcl(\Zend_Acl $acl = null)
	{
		if (null === $acl) {
			$acl = \Zend_Registry::getInstance()->Acl;
		}
        if (!$acl instanceof \Zend_Acl) {
            throw new Exception\BadMethodCallException('Could not set the Acl');
        }
		$this->_acl = $acl;
		return $this;
	}

    /**
     * Return the validator
     * @return Zend_Validator
     */
    public function getValidator()
	{
		return $this->_validator;
	}
	
    public function setValidator(\Zend_Validate_Interface $validator)
	{
		$this->_validator = $validator;
		return $this;
	}

    /**
     * Get the Mapper instance
     * @return \Net\SylvainBoucher\Models\Mapper\MapperInterface
     */
    protected function _getMapper()
	{
		return $this->_mapper;
	}

    /**
     *
     * @param \Net\SylvainBoucher\Models\Mapper\MapperInterface $mapper
     * @return ServiceAbstract
     */
    protected function _setMapper(
		\Net\SylvainBoucher\Models\Mappers\MapperInterface $mapper
	) {
		$this->_mapper = $mapper;
		return $this;
	}

    /**
     * Get the cache instance
     * @return Zend_Cache_Core
     */
    protected function _getCache()
	{
		return $this ->_cache; 	
	}

    /**
     * Set the cache instance
     * @param \Zend_Cache_Core $cache
     * @return ServiceAbstract
     */
    protected function _setCache(\Zend_Cache_Core $cache)
	{
		$this->_cache = $cache;
		return $this;
	}

    /**
     * Clear the cache
     * @return ServiceAbstract
     */
    protected function _clearCache()
	{
		$this->_getCache()->clean(
			\Zend_Cache::CLEANING_MODE_MATCHING_TAG,
			array($this->_getServiceId())
		);
        return $this;
	}

    /**
     * Get the cache tag
     * @return string
     */
    protected function _getServiceId()
    {
        if (null === $this->_serviceId) {
            $this->_setServiceId();
        }
        return $this->_serviceId;
    }

    /**
     * Set the cache tag
     * @param string $tag
     * @return ServiceAbstract
     */
    protected function _setServiceId($tag = null)
    {
        if (null === $tag) {
            $serviceId = str_replace('\\', '', __CLASS__);
        }

        $this->_serviceId = (string) $serviceId;
        return $this;
    }

    /**
     *  Find the entity in the storage system
     * @param int $key
     * @return \Net\SylvainBoucher\Models\EntityInterface
     */
    protected function _find($key)
	{
		$entityClass = $this->_getEntityClass();
		$entity = new $entityClass();
		return $this->_getMapper()->find($key, $entity);
	}

    /**
     *
     * @param array $data
     * @return  \Net\SylvainBoucher\Models\EntityInterface
     */
	protected function _save(array $data)
	{
			$entityClass = $this->_getEntityClass();
			$entity = new $entityClass();
			$entity->fromArray($data);
            $this->_clearCache();
			return $this->_getMapper()->save($entity);	
	}

    /**
     * Query the validator to check if the data is valid
     * @param array $data
     * @return bool
     */
    protected function _isValid(array $data)
	{
		return $this->getValidator()->isValid($data);
	}

    /**
     *  Check if the current User is allowed to performed the privilege
     * @param mixed $role
     * @param mixed $resource
     * @param string $privilege
     * @return bool
     * @throws Net\SylvainBoucher\Services\Exception\AccessDeniedException
     */
    protected function _isAllowed(
        $role = null,
        $resource = null,
        $privilege = null
    ) {
		$result =  $this->getAcl()->isAllowed($role, $resource, $privilege);
        if (false !== $result) {
            return true;
        }
        throw new Exception\AccessDeniedException('Access denied');
	}

    /**
     *  Set the name of the entity class associated eith this service
     * @param string $entityClass
     * @return ServiceAbstract
     */
    protected function _setEntityClass($entityClass)
	{
		$this->_entityClass = (string) $entityClass;
		return $this;
	}

    /**
     * Get the name of the entity class associated eith this service
     * @return string
     */
    protected function _getEntityClass()
	{
		return $this->_entityClass;
	}

    /**
     * Query the User service to get the current user
     * @return \Net\SylvainBoucher\Models\User
     */
    protected function _getCurrentUser()
	{
        $serviceManager = \Zend_Registry::getInstance()->ServiceManager;
        $userContainer = $serviceManager->getServiceContainer(
                'Net\SylvainBoucher\Services\UserContainer'
            );
		$userService = $userContainer->getService('user');
		return $userService->getCurrentUser();
	}

    /**
     *  Returns the config options
     * @param string $key
     * @return array
     */
    protected function _getOptions($key = null)
    {
        $fc = \Zend_Controller_Front::getInstance();
        $bootstrap = $fc->getParam('bootstrap');
        $options = $bootstrap->getOptions();
        if (null !== $key) {
            $options = $options[$key];
        }
        return $options;
    }

    /**
     * Return the Zend_Search_Lucene index
     * @return Zend_Search_Lucene
     */
    public function getSearchIndex()
    {
        if ($this->_searchIndex === null) {
            $options = $this->_getOptions('lucene');
            try {
                $this->_searchIndex = \Zend_Search_Lucene::open($options['basepath']);
            } catch (\Zend_Search_Lucene_Exception $e) {
                $this->_searchIndex = \Zend_Search_Lucene::create($options['basepath']);
            }
        }
        return $this->_searchIndex;
    }

    /**
     * Update the Lucene index
     * @param string $key
     * @param string $mode
     * @param Zend_Search_Lucene_Document $document
     */
    protected function _updateSearchIndex($mode, $entity)
    {
        $key = $this->_getServiceId() . $entity->getId();
        if ($mode === 'delete' || $mode === 'update') {
            $hits = $this->getSearchIndex()->find("key:  $key");
            foreach ($hits as $hit) {
                $this->getSearchIndex()->delete($hit->id);
            }
        }
        if ($mode !== 'delete') {
            $this->getSearchIndex()->addDocument($this->_getIndexDocument($key, $entity));
        }
        $this->getSearchIndex()->commit();
        $this->getSearchIndex()->optimize();
    }
    protected function _getIndexDocument($key, $entity)
    {
        $doc = new \Zend_Search_Lucene_Document();
        $doc->addField(\Zend_Search_Lucene_Field::Keyword('key', $key));
        $data = $entity->toArray();
        foreach ($data as $key => $value) {
            if ($key === 'id') {
                continue;
            }
            $doc->addField(\Zend_Search_Lucene_Field::Text($key, $value, 'UTF-8'));
        }
        return $doc;
    }
}
