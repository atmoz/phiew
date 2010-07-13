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
 * Transport and validate your data as an custom ArrayObject
 */
abstract class Phiew_Model_DataAbstract extends ArrayObject
{
	/**
	 * Contains all validate errors
	 * @var array
	 */
	protected $_validateErrors;

	/**
	 * Validate all fields
	 *
	 * @return boolean
	 */
	public function validate()
	{
		$this->clearAllValidateErrors();

		foreach ($this as $index => $field)
		{
			$this->offsetValidate($index);
		}

		return !(boolean) count($this->getAllValidateErrors());
	}

	/**
	 * Add validate error for a field
	 *
	 * @param string $index Field index/name
	 * @param string $message
	 */
	public function setValidateError($index, $message)
	{
		$this->_validateErrors[$index] = $message;
	}

	/**
	 * Get validate error for a field, if any
	 *
	 * @param string $index
	 * @return string|null
	 */
	public function getValidateError($index)
	{
		if (isset($this->_validateErrors[$index]))
		{
			return $this->_validateErrors[$index];
		}
		else
		{
			return null;
		}
	}

	/**
	 * Get all validate errors as array
	 *
	 * @return array
	 */
	public function getAllValidateErrors()
	{
		return (array) $this->_validateErrors;
	}

	/**
	 * Reset/remove all validate errors
	 */
	public function clearAllValidateErrors()
	{
		$this->_validateErrors = array();
	}

	/**
	 * If undefined function is called, check if function is supposed to validate
	 * a field. If so, the field does not require validation, and defaults to
	 * successfull validation (return true)
	 *
	 * @param string $name
	 * @param array $arguments
	 * @return mixed
	 */
	public function __call($name,  $arguments)
	{
		if (substr($name, 0, 8) == 'validate')
		{
			if ($this->offsetExists(substr($name, 8)))
			{
				return true;
			}
			else
			{
				trigger_error("Field '$field' does not exist!", E_USER_WARNING);
				return false;
			}
		}
		else
		{
			trigger_error("Method '$name' does not exist!", E_USER_ERROR);
		}
	}

	/**
	 * Call a function name with prefix
	 *
	 * @param string $prefix
	 * @param string $name
	 * @param array $parameters
	 * @return mixed
	 */
	protected function _callPrefixedFunction($prefix, $name, $parameters = array())
	{
		return call_user_func_array(array($this, $prefix . ucfirst($name)), $parameters);
	}

	/**
	 * Call a field validator function
	 *
	 * @param string $index
	 * @return boolean
	 */
	protected function _callValidateFunction($index)
	{
		return $this->_callPrefixedFunction('validate');
	}

	/**
	 * Call a field getter function
	 *
	 * @param string $index
	 * @return mixed
	 */
	protected function _callGetFunction($index)
	{
		return $this->_callPrefixedFunction('get', $index);
	}

	/**
	 * Call a field setter function
	 *
	 * @param string $index
	 * @return mixed
	 */
	protected function _callSetFunction($index, $value)
	{
		return $this->_callPrefixedFunction('set', $index, array($value));
	}

	/**
	 * Validate data by field name
	 *
	 * @param string $index
	 * @return boolean
	 */
	public function offsetValidate($index)
	{
		return $this->_callValidateFunction($index);
	}

	/**
	 * append() is disabled!
	 */
	protected function append() {}

	/**
	 * Check if a field exists
	 *
	 * @param string $index
	 * @return boolean
	 */
	public function offsetExists($index) 
	{
		return in_array($this->_getGetFunction($index), get_class_methods($this));
	}

	/**
	 * Get a field value
	 *
	 * @param string $index
	 */
	public function offsetGet($index)
	{
		if ($this->offsetExists($index))
		{
			$this->_callGetFunction($index);
		}
	}

	/**
	 * Set a field value
	 *
	 * @param string $index
	 */
	public function offsetSet($index, $value)
	{
		if ($this->offsetExists($index))
		{
			$this->_callSetFunction($index, $value);
		}
	}

	/**
	 * Unset a field value by setting it to null
	 *
	 * @param string $index
	 */
	public function offsetUnset($index)
	{
		if ($this->offsetExists($index))
		{
			$this->{$this->_getSetFunction($index)}(null);
		}
	}
}
