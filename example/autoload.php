<?php

// First add the library to include path
set_include_path(
	realpath(dirname(__FILE__) . '/../library') . 
	PATH_SEPARATOR . get_include_path() 
);

// Then register a simple autoloader converting _ to /
function example_autoload($className)
{
    include str_replace(array('\\', '_'),  '/', $className) . '.php';
}
spl_autoload_register('example_autoload');