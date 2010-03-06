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
 * Static use of templates
 */
class Phiew_View
{
	protected static $_templateFolder;

	public static function setTemplateFolder($folder)
	{
		self::$_templateFolder = $folder;
	}

	public static function getTemplateFolder()
	{
		return self::$_templateFolder;
	}

	public static function render($view, $data = array())
	{
		$template = new Phiew_View_Template();
		$template->setTemplateFolder(self::getTemplateFolder());
		$template->render($view, $data);
	}

	public static function capture($view, $data = array())
	{
		$template = new Phiew_View_Template();
		$template->setTemplateFolder(self::getTemplateFolder());
		return $template->capture($view, $data);
	}
}
