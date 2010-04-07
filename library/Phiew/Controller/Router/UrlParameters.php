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
 * Uses URL parameters to route the request
 */
class Phiew_Controller_Router_UrlParameters implements Phiew_Controller_RouterInterface
{
	/**
	 * @var array
	 */
	protected $_settings;
	
	/**
	 * @var string
	 */
	protected static $_controller;

	/**
	 * @var string
	 */
	protected static $_action;
	
	/**
	 * Setup
	 */
	public function __construct($settings = array())
	{
		$defaultSettings = array(
			'controller_parameter' => 'c',
			'action_parameter'     => 'a'
		);
		$this->_settings = array_merge($defaultSettings, $settings);
	}

	/**
	 * @return string
	 */
	public function getController()
	{
		if (empty(self::$_controller))
		{
			self::$_controller = isset($_REQUEST[$this->_settings['controller_parameter']])
						       ? $_REQUEST[$this->_settings['controller_parameter']]
						       : 'index';
		}
		return self::$_controller;
	}

	/**
	 * @return string
	 */
	public function getAction()
	{
		if (empty(self::$_action))
		{
			self::$_action = isset($_REQUEST[$this->_settings['action_parameter']])
					       ? $_REQUEST[$this->_settings['action_parameter']]
					       : 'index';
		}
		return self::$_action;
	}

	/**
	 * @return array
	 */
	public function getParameters()
	{
		return array();
	}

	/**
	 * @param string $controller
	 * @param string $action
	 * @param array $parameters
	 * @return string
	 */
	public function generateUrl($controller = null, $action = null, $parameters = array())
	{
		if (empty($controller))
		{
			$controller = $this->getController();
			
			if (empty($action))
			{
				$action = $this->getAction();
			}
		}
		else if (empty($action))
		{
			$action = $this->getAction();
		}

		$url = sprintf('?%s=%s&%s=%s',
			urlencode($this->_settings['controller_parameter']), urlencode($controller),
			urlencode($this->_settings['action_parameter']), urlencode($action)
		);

		foreach ($parameters as $parameter => $value)
		{
			$url .= '&' . urlencode($parameter) . '=' . urlencode($value);
		}

		return $url;
	}
}
