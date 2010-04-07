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
	/**
	 * @var array
	 */
	protected $_settings;
	
	/**
	 * @var Phiew_Controller_RouterInterface
	 */
	protected $_router;

	/**
	 * Configure bootstrap
	 *
	 * @param array $settings
	 */
	public function __construct($settings = array())
	{
		$defaultSettings = array(
			'application_folder' => null,
			'router'             => new Phiew_Controller_Router_UrlParameters()
		);

		$this->_settings = array_merge($defaultSettings, $settings);
		$this->_router   = $this->_settings['router'];
	}

	/**
	 * Delegate to a controller
	 *
	 * @throws Exception
	 */
	public function delegate()
	{
		// application_folder must exist
		if (!is_dir(realpath($this->_settings['application_folder'])))
		{
			throw new Exception('Application folder does not exist: '
				. $this->_settings['application_folder']);
		}

		$controllerFolder = realpath($this->_settings['application_folder'] . '/Controllers');
		if ($controllerFolder === false)
		{
			throw new Exception('Controller folder failed: ' . $this->_settings['controllers']);
		}

		$controllerName = ucfirst($this->_router->getController()) . 'Controller';
		$controllerPath = $controllerFolder . '/' . $controllerName . '.php';
		$actionName     = strtolower($_SERVER['REQUEST_METHOD']) . ucfirst($this->_router->getAction());

		if (!is_readable($controllerPath))
		{
			throw new Exception('Could not read controller file: ' . $controllerPath);
		}

		include_once $controllerPath;
		$controller = new $controllerName($this->_router);

		if (is_callable(array($controller, $actionName)))
		{
			$controller->$actionName();
		}
		else
		{
			throw new Exception('Action does not exist: ' . $controllerName . '::' . $actionName . '()');
		}
	}
}
