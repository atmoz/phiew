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
 * Holds the MVC parts together
 */
class Phiew_Application
{
	/**
	 * @var string
	 */
	protected $_applicationFolder;

	/**
	 * @var Phiew_Controller_RouterInterface
	 */
	protected $_router;

	/**
	 * Configure application
	 *
	 * @param array $values
	 */
	public function __construct($values = array())
	{
		$defaultValues = array(
			'applicationFolder' => null,
			'router'            => new Phiew_Controller_Router_UrlParameters()
		);
		$values = array_merge($defaultValues, $values);

		$this->setApplicationFolder($values['applicationFolder']);
		$this->setRouter($values['router']);
	}
	
	public function getApplicationFolder() {
		return $this->_applicationFolder;
	}
	
	public function setApplicationFolder($applicationFolder) {
		$this->_applicationFolder = $applicationFolder;
	}

	public function getRouter()
	{
		return $this->_router;
	}

	public function setRouter(Phiew_Controller_RouterInterface $router)
	{
		$this->_router = $router;
	}

	/**
	 * Bootstrap the applocation. Delegates to a controller based on request.
	 *
	 * @throws Exception
	 */
	public function bootstrap()
	{
		// application_folder must exist
		if (!is_dir(realpath($this->getApplicationFolder())))
		{
			throw new Exception('Application folder does not exist: '
				. $this->getApplicationFolder());
		}

		$controllerFolder = $this->getApplicationFolder() . '/Controllers';
		if (realpath($controllerFolder) === false)
		{
			throw new Exception('Controller folder failed: ' . $controllerFolder);
		}

		$controllerName = ucfirst($this->getRouter()->getController()) . 'Controller';
		$controllerPath = $controllerFolder . '/' . $controllerName . '.php';
		$actionName     = strtolower($_SERVER['REQUEST_METHOD']) . ucfirst($this->getRouter()->getAction());

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
			throw new Exception('Action does not exist: ' . $controllerName . '::' . $actionName . '()');
		}
	}
}
