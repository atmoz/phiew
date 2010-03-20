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
 * Makes your controllers easier to use
 */
class Phiew_Controller extends Phiew_Controller_State
{
	/**
	 * Render template
	 *
	 * @param string $view
	 * @param array $data
	 * @return string|null
	 */
	protected function _render($view, $data = array())
	{
		if (empty($data))
		{
			$data = (array) $this;
		}

		return Phiew_View::render($view, $data);
	}

	/**
	 * Render template based on controller action. Using class name as folder.
	 *
	 * @param string $action
	 * @param array $data
	 * @return string|null
	 */
	protected function _renderAction($action, $data = array())
	{
		return $this->_render(get_class($this) . '/' . $action, $data);
	}

	/**
	 * Render template based on current controller action (calling function)
	 *
	 * @param array $data
	 * @return string|null
	 */
	protected function _renderCurrentAction($data = array())
	{
		// Get calling function name
		$trace = debug_backtrace();
		$function = $trace[1]['function'];

		return $this->_renderAction($function, $data);
	}
}
