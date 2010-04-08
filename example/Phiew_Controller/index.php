<?php

// Register autoloader
require_once '../../library/Phiew/Autoload.php';
Phiew_Autoload::register();

// Folder where Phiew_View will look for templates
Phiew_View::setTemplateFolder(dirname(__FILE__) . '/Views');

try // bootstrapping the application
{
	$application = new Phiew_Application(array(
		'application_folder' => dirname(__FILE__)
	));
	$application->bootstrap();
}
catch (Exception $e)
{
	echo $e->getMessage();
}
