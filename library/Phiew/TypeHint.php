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
 * Type hinting
 */
class Phiew_TypeHint
{
	protected $_type;
	protected $_value;

	/**
	 * Sets type and a default value
	 *
	 * @param string $type
	 * @param mixed $value
	 * @throws Phiew_TypeHint_WrongTypeException
	 */
	public function __construct($type, $value = null)
	{
		$this->setType($type);
		$this->setValue($value);
	}

	/**
	 * Set type rule
	 *
	 * @param string $type
	 */
	public function setType($type)
	{
		$this->_type = $type;
	}

	/**
	 * Get type rule
	 *
	 * @return string
	 */
	public function getType()
	{
		return $this->_type;
	}

	/**
	 * Set value
	 *
	 * @param mixed $value
	 * @throws Phiew_TypeHint_WrongTypeException
	 */
	public function setValue($value)
	{
		if ($this->_validateValue($value))
		{
			$this->_value = $value;
		}
		else
		{
			throw new Phiew_TypeHint_WrongTypeException(
				'Value is not of type "' . $this->getType() . '"');
		}
	}

	/**
	 * Get value
	 *
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->_value;
	}

	/**
	 * Validate the value against the type hint rule
	 *
	 * @param mixed $value
	 * @param string $type
	 * @return boolean
	 */
	protected function _validateValue($value)
	{
		$validate = array();
		$typeArray = (array) explode('|', $this->getType());
		foreach ($typeArray as $type)
		{
			$validate[] = $this->_valueIsType($value, $type);
		}

		return in_array(true, $validate);
	}

	/**
	 * Check if the value is a certain type
	 *
	 * @param mixed $value
	 * @param string $type
	 * @return boolean
	 */
	protected function _valueIsType($value, $type)
	{
		if (empty($type) || is_null($value))
		{
			return true;
		}

		switch ($type)
		{
			case 'string':   return is_string($value);
			case 'number':   return is_numeric($value);
			case 'integer':  return is_int($value);
			case 'float':    return is_float($value);
			case 'boolean':  return is_bool($value);
			case 'resource': return is_resource($value);
			case 'scalar':   return is_scalar($value);
			case 'array':    return is_array($value);
			case 'object':   return is_object($value);
			default:         return is_a($value, $type);
		}
	}
}
