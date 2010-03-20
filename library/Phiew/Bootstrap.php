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
 * Bootstrap the application
 */
class Phiew_Bootstrap
{
	const ROUTING_METHOD_PARAMETERS = 1;
	const ROUTING_METHOD_URI = 2;

	protected $_settings;

	/**
	 * Configure bootstrap
	 *
	 * @param array $settings
	 */
	public function __construct($settings = array())
	{
		// Where is this object used?
		$trace = debug_backtrace(false);
		$file = $trace[0]['file'];

		$defaultSettings = array(
			'application_folder' => dirname($file),
			'routing_method'     => self::ROUTING_METHOD_PARAMETERS
		);

		$this->_settings = array_merge($defaultSettings, $settings);
	}

	/**
	 * Delegate to a controller
	 *
	 * @throws Exception
	 */
	public function delegate()
	{
		$controllerFolder = realpath($this->_settings['application_folder'] . '/Controllers');

		if ($controllerFolder === false)
		{
			throw new Exception('Controller folder failed: ' . $this->_settings['controllers']);
		}

		switch ($this->_settings['routing_method'])
		{
			case self::ROUTING_METHOD_PARAMETERS:
				$controllerName = isset($_REQUEST['controller'])
				                ? $_REQUEST['controller']
				                : 'index';
				$controllerName .= 'Controller';
				$actionName = isset($_REQUEST['action'])
				            ? $_REQUEST['action']
				            : 'index';
				break;
		}

		$controllerPath = $controllerFolder . '/' . ucfirst($controllerName) . '.php';

		if (!is_readable($controllerPath))
		{
			throw new Exception('Could not read controller file: ' . $controllerPath);
		}

		include_once $controllerPath;
		$controller = new $controllerName();

		if (is_callable(array($controller, $actionName)))
		{
			$controller->$actionName();
		}
		else
		{
			throw new Exception('Action does not exist!');
		}
	}
}
