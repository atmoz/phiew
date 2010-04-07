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
 * The router get all we need to route the request
 */
interface Phiew_Controller_RouterInterface
{
	/**
	 * @return string
	 */
	public function getController();

	/**
	 * @return string
	 */
	public function getAction();

	/**
	 * @return array
	 */
	public function getParameters();

	/**
	 * @param string $controller
	 * @param string $action
	 * @param array $parameters
	 * @return string
	 */
	public function generateUrl($controller = null, $action = null, $parameters = array());
}
