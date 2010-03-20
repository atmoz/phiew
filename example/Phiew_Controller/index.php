<?php

// Register autoloader
require_once '../../library/Phiew/Autoload.php';
Phiew_Autoload::register();

// Folder where Phiew_View will look for templates
Phiew_View::setTemplateFolder(dirname(__FILE__) . '/Views');

try // bootstrapping the application
{
	$bootstrap = new Phiew_Bootstrap();
	$bootstrap->delegate();
}
catch (Exception $e)
{
	echo $e->getMessage();
}
