<?php
/**
 * @author Adrian Dvergsdal
 * @link http://github.com/atmoz/phiew
 * @license http://creativecommons.org/licenses/by-sa/3.0/us/
 */

/**
 * Static use of templates
 */
class Phiew_View
{
	public static function render($view, $data = array())
	{
		$template = new Phiew_View_Template();
		$template->render($view, $data);
	}

	public static function capture($view, $data = array())
	{
		$template = new Phiew_View_Template();
		return $template->capture($view, $data);
	}
}