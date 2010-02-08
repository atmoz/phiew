<?php
/**
 * @author Adrian Dvergsdal
 * @link http://github.com/atmoz/phiew
 * @license http://creativecommons.org/licenses/by-sa/3.0/us/
 */

/**
 * The main class for rendering of view scripts
 */
class Phiew_View_Template
{
	protected $_dirname = null;
	protected $_data = array();
	protected static $_helpers = array();

	/**
	 * Constructor
	 *
	 * @param string $dirname Location for view script folder
	 */
	public function __construct($dirname = null)
	{
		if (is_null($dirname) && defined('PHIEW_VIEW_DIR'))
		{
			$this->setDirname(PHIEW_VIEW_DIR);
		}
		else
		{
			$this->setDirname($dirname);
		}
	}

	/**
	 * Check input for bad characters
	 *
	 * @param string $string
	 * @return string
	 */
	protected function _getSafePath($string)
	{
		$allowedChars = array('/', '.', '-', '_');

		if (is_null($string))
		{
			return null; // Skip the validation (ctype_alnum() makes it fail anyway)
		}
		else if (ctype_alnum(str_replace($allowedChars, '', $string)) && strpos($string, '..') === false) 
		{
			return $string;
		}
		else
		{
			trigger_error('Bad characters used in path: ' . $string, E_USER_ERROR);
		}
	}

	/**
	 * Set folder for view scripts
	 *
	 * @param string $dirname
	 */
	public function setDirname($dirname)
	{
		$this->_dirname = rtrim($this->_getSafePath($dirname), '/');
	}
	
	/**
	 * Get view folder
	 * 
	 * @return string
	 */
	public function getDirname()
	{
		return $this->_dirname;
	}

	/**
	 * Generate full file path for view script
	 *
	 * @param string $view
	 * @return string
	 */
	protected function _getFilename($view)
	{
		$view = $this->_getSafePath($view);
		
		if (substr($view, -6) == '.phtml')
		{
			// Assume full path is given when using .phtml
			return $view;
		}
		else
		{
			return $this->getDirname() . '/' . $view . '.phtml';
		}
	}

	/**
	 * Set data
	 *
	 * @param string $name
	 * @param mixed $value
	 */
	public function __set($name, $value)
	{
		$this->_data[$name] = $value;
	}

	/**
	 * Get data
	 *
	 * @param string $name
	 * @return mixed
	 */
	public function __get($name)
	{
		if (array_key_exists($name, $this->_data))
		{
			return $this->_data[$name];
		}
		else
		{
			trigger_error('Missing view data: ' . $name, E_USER_WARNING);
		}
	}

	/**
	 * Clear data array
	 */
	public function clearData()
	{
		$this->_data = array();
	}
	
	/**
	 * Replace data array
	 * 
	 * @param array $data
	 */
	public function setData(array $data)
	{
		$this->clearData();
		
		foreach ($data as $key => $value)
		{
			$this->__set($key, $value);
		}
	}
	
	/**
	 * Get data array
	 * 
	 * @return array
	 */
	public function getData()
	{
		return $this->_data;
	}

	/**
	 * Output view script
	 *
	 * @param string $view
	 * @param array $data
	 * @return boolean
	 */
	public function render($view, array $data = array())
	{
		$filename = $this->_getFilename($view);

		if (is_readable($filename))
		{
			$this->setData($data);
			return (bool) include $filename;
		}
		else
		{
			trigger_error('Could not find view script: ' . $filename, E_USER_ERROR);
		}
	}

	/**
	 * Get output from view script as string
	 *
	 * @param string $view
	 * @param array $data
	 * @return string
	 */
	public function capture($view, $data = array())
	{
		ob_start();
		$this->render($view, $data);
		return ob_get_clean();
	}

	/**
	 * Escape html characters
	 *
	 * @param string $string
	 */
	public function escape($string)
	{
		return htmlspecialchars($string);
	}

	/**
	 * Try to load and call a view helper
	 */
	public function __call($function, $args)
	{
		// No bullshitting, m'kay?
		if (!ctype_alnum($function))
		{
			trigger_error('That\'s not a pretty name for a function, is it?', E_USER_ERROR);
		}

		$helperKey = strtolower($function);

		// Make sure we have that view helper loaded
		if (!isset(self::$_helpers[$helperKey]))
		{
			$helperClass = 'Phiew_View_Helper_' . ucfirst($function);
			$helperFile = dirname(__FILE__) . '/Helper/' . ucfirst($function) . '.php';
			
			if (is_readable($helperFile))
			{
				require_once $helperFile;
				self::$_helpers[$helperKey] = eval("return new $helperClass();");
			}
		}

		// Call view helper
		$helper = self::$_helpers[$helperKey];
		if (is_callable(array($helper, $function)))
		{
			return call_user_func_array(array($helper, $function), $args);
		}
		else
		{
			trigger_error("Could not load view helper \"$function\"", E_USER_ERROR);
		}
	}
}
