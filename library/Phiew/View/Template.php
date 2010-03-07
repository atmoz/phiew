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
 * The main class for rendering of view templates
 */
class Phiew_View_Template
{
	protected $_templateFolder = null;
	protected $_data = array();
	protected static $_helpers = array();

	/**
	 * Constructor
	 *
	 * @param string $templateFolder Location for template folder
	 */
	public function __construct($templateFolder = null)
	{
		$this->setTemplateFolder($templateFolder);
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

		if (empty($string))
		{
			return null; // Skip the validation (ctype_alnum() makes it fail anyway)
		}
		else if (ctype_alnum(str_replace($allowedChars, '', $string))) 
		{
			return $string;
		}
		else
		{
			trigger_error('Bad characters used in path: ' . $string, E_USER_ERROR);
		}
	}

	/**
	 * Set folder for view templates
	 *
	 * @param string $templateFolder
	 */
	public function setTemplateFolder($templateFolder)
	{
		$this->_templateFolder = $templateFolder;
	}
	
	/**
	 * Get view folder
	 * 
	 * @return string
	 */
	public function getTemplateFolder()
	{
		if (empty($this->_templateFolder) && defined('PHIEW_VIEW_TEMPLATE_FOLDER'))
		{
			$templateFolder = PHIEW_VIEW_TEMPLATE_FOLDER;
		}
		else
		{
			$templateFolder = $this->_templateFolder;
		}
		
		return realpath( $this->_getSafePath( $templateFolder ) );
	}

	/**
	 * Generate full file path for view template
	 *
	 * @param string $view
	 * @return string
	 */
	protected function _getFilename($view)
	{
		$view      = $this->_getSafePath($view);
		$extension = (substr($view, -6) == '.phtml' ? '' : '.phtml');

		if (substr($view, 0, 1) != '/' && $this->getTemplateFolder())
		{
			$view = $this->getTemplateFolder() . '/' . $view;
		}

		return $view . $extension;
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
	public function setData($data)
	{
		$this->clearData();
		
		foreach ((array)$data as $key => $value)
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
	public function render($view, $data = array())
	{
		$filename = $this->_getFilename($view);

		if (is_readable($filename))
		{
			$this->setData($data);
			return (bool) include $filename;
		}
		else
		{
			trigger_error('Could not find view script: ' . $filename, E_USER_WARNING);
			return false;
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
