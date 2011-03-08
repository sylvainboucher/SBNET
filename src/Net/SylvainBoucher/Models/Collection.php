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
 * Collection class that holds an array of entity objects
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
 * Collection class that holds an array of entity objects
 *
 * @category   Net\SylvainBoucher
 * @package    Models
 * @copyright  Copyright (c) 2011 SylvainBoucher
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id:$
 * @since      File available since Release 0.4
 *
 */
class Collection implements CollectionInterface
    {

        /**
         * Holds the entities
         *
         * @var array
         */
        protected $_entities = array();

        /**
         *
         * @return array the entities
         */
        public function toArray()
        {
            return $this->_entities;
        }

        /**
         * Add an entity to the storage
         * @param \Net\SylvainBoucher\Models\EntityInterface $entity
         * @return \Net\SylvainBoucher\Models\CollectionInterface
         */
        public function addEntity(\Net\SylvainBoucher\Models\EntityInterface $entity)
        {
            $this->_entities[] = $entity;
            return $this;
        }
        
        /**
         * @see    Countable::count()
         * @return integer
         */
        public function count()
        {
            return count($this->_entities);
        }

        /**
         * @see    SeekableIterator::seek()
         * @param  integer $index
         * @throws \Net\SylvainBoucher\Models\Exception\OutOfBoundsException
         * @return void
         */
        public function seek($index)
        {
            $this->rewind();
            $position = 0;

            while ($position < $index && $this->valid()) {
                $this->next();
                $position++;
            }

            if (!$this->valid()) {
                throw new Exception\OutOfBoundsException('Invalid seek position');
            }
        }

        /**
         * @see    SeekableIterator::current()
         * @return mixed
         */
        public function current()
        {
            return current($this->_entities);
        }

        /**
         * @see    SeekableIterator::next()
         * @return mixed
         */
        public function next()
        {
            return next($this->_entities);
        }

        /**
         * @see    SeekableIterator::key()
         * @return mixed
         */
        public function key()
        {
            return key($this->_entities);
        }

        /**
         * @see    SeekableIterator::valid()
         * @return boolean
         */
        public function valid()
        {
            return ($this->current() !== false);
        }

        /**
         * @see    SeekableIterator::rewind()
         * @return void
         */
        public function rewind()
        {
            reset($this->_entities);
        }
    }
