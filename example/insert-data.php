<?php

require_once 'autoload.php';

Phiew_View::render('views/insert-data', array(
	'title' => 'Adding data to our view',
	'list'  => array('Some', 'example', 'data', 'for', 'you')
));
