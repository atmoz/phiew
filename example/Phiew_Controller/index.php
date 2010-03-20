<?php

// Register autoloader
require_once '../../library/Phiew/Autoload.php';
Phiew_Autoload::register();

try // bootstrapping the application
{
	$bootstrap = new Phiew_Bootstrap();
	$bootstrap->delegate();
}
catch (Exception $e)
{
	echo $e->getMessage();
}
