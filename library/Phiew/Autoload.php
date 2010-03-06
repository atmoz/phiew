<?php
/**
 * @author Adrian Dvergsdal
 * @link http://github.com/atmoz/phiew
 * @license http://creativecommons.org/licenses/by-sa/3.0/us/
 */

/**
 * Autoload Phiew classes
 */
class Phiew_Autoload
{
	/**
	 * Adds this class to the SPL autoload stack
	 */
	public static function register()
	{
		spl_autoload_register('Phiew_Autoload::loadClass');
	}

	/**
	 * Loads the required class
	 *
	 * @param string $className
	 */
	public static function loadClass($className)
	{
		$phiewFolder = realpath( dirname(__FILE__) . '/..' );
		$classPath   = str_replace('_',  '/', $className) . '.php';
		$file        = $phiewFolder . '/' . $classPath;

		if (is_readable($file))
		{
			require $file;
		}
	}
}
