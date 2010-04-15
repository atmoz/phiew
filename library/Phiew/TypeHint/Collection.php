<?php
/**
 * Phiew
 * - Simple and fast MVC components for PHP 5
 *
 * @version 0.12.0
 * @author Adrian Dvergsdal
 * @link http://github.com/atmoz/phiew
 * @license http://creativecommons.org/licenses/by-sa/3.0/us/
 */

/**
 * Simple type hinting to collection of values
 *
 * @param array $values
 */
class Phiew_TypeHint_Collection extends ArrayObject
{
	public function __construct(array $values = array())
	{
		foreach ($values as $index => $value)
		{
			$this->offsetSet($index, $value);
		}
		
		parent::__construct($values);
	}

	/**
	 * Merge with a array.
	 *
	 * @param array $array
	 * @return Phiew_TypeHint_Collection Merged collection
	 */
	public function mergeArray(array $array)
	{
		foreach ($array as $index => $value)
		{
			$this->offsetSet($index, $value);
		}
	}

	/**
	 * Set a value
	 *
	 * @param mixed $index
	 * @param mixed $value
	 * @throws Phiew_TypeHint_WrongTypeException
	 */
	public function offsetSet($index, $value)
	{
		if ($this->$index instanceof Phiew_TypeHint)
		{
			$this->$index->setValue($value);
		}
		else
		{
			$this->$index = $value;
		}
	}

	/**
	 * Get a value
	 *
	 * @param mixed $index
	 * @return mixed
	 */
	public function offsetGet($index)
	{
		if ($this->$index instanceof Phiew_TypeHint)
		{
			return $this->$index->getValue();
		}
		else
		{
			return $this->$index;
		}
	}
}
