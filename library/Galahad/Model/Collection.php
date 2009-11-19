<?php
/**
 * This file is part of the Galahad Framework Extension.
 * 
 * The Galahad Framework Extension is free software: you can redistribute 
 * it and/or modify it under the terms of the GNU General Public License 
 * as published by the Free Software Foundation, either version 3 of the 
 * License, or (at your option) any later version.
 * 
 * The Galahad Framework Extension is distributed in the hope that it will
 * be useful, but WITHOUT ANY WARRANTY; without even the implied warranty
 * of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU 
 * General Public License for more details.
 * 
 * @category  Galahad
 * @package   Galahad
 * @copyright Copyright (c) 2009 Chris Morrell <http://cmorrell.com>
 * @license   GPL <http://www.gnu.org/licenses/>
 * @version   0.3
 */

/**
 * Provides a basic wrapper around an array of Entities
 * 
 * @category   Galahad
 * @package    Galahad_Model
 * @copyright  Copyright (c) 2009 Chris Morrell <http://cmorrell.com>
 * @license    GPL <http://www.gnu.org/licenses/>
 */
class Galahad_Model_Collection extends Galahad_Model implements Iterator, Countable, ArrayAccess
{
    /**
     * Collection storage
     * @var array
     */
	protected $_entities = array();
	
	/**
	 * Current count of entities
	 * @var integer
	 */
	protected $_count = 0;
	
	/**
	 * Constructor
	 * 
	 * @param array|object $entities Either an array or an object that implemetns Iterator & Countable
	 * @param array $options
	 */
	public function __construct($entities)
	{
		$this->setEntities($entities);
	}
	
	/**
	 * Sets the collection's entities
	 * 
	 * @param array|object $entities Either an array or an object that implemetns Iterator & Countable
	 * @return Galahad_Model_Collection
	 */
	public function setEntities($entities)
	{
		if (!is_array($entities) && (!$entities instanceof Iterator || !$entities instanceof Countable)) {
		    // TODO: Use a better Exception class
			throw new Exception('Collection entities must be an array or implement Iterator and Countable');
		}
		
		$this->_entities = $entities;
		$this->_count = count($entities);
		
		return $this;
	}
	
	/**#@+
	 * Implementation of Iterator
	 */
	public function current() 
	{
	    return current($this->_entities);
	}
	
	public function key()
	{
	    return key($this->_entities);
	}
	
	public function next()
	{
	    next($this->_entities);
	}
	
	public function rewind()
	{
	    reset($this->_entities);
	}
	
	public function valid()
	{
	    return (null !== $this->key());
	}
	/**#@-*/
	
	/**#@+
	 * Implementation of ArrayAccess
	 */
	public function offsetExists($offset)
	{
	    return isset($this->_entities[$offset]);
	}
	
	public function offsetGet($offset)
	{
	    return ($this->offsetExists($offset) ? $this->_entities[$offset] : null);
	}
	
	public function offsetSet($offset, $value)
	{
	    $this->_entities[$offset] = $value;
        $this->_count = count($this->_entities);
	}
	
	public function offsetUnset($offset)
	{
	    unset($this->_entities[$offset]);
	    $this->_count = count($this->_entities);
	}
	/**#@-*/
	
	/**
	 * Implementation of Countable
	 */
	public function count()
	{
	    return count($this->_entities);
	}
}