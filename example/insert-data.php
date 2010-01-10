<?php

require_once '../library/Phiew/View.php';

$view = new Phiew_View(dirname(__FILE__) . '/views');
$view->render('insert-data', array(
	'title' => 'Adding data to our view',
	'list' => array('Some', 'example', 'data', 'for', 'you')
));


/* Alternative method for setting data:
 * 
 * $view->title = 'Adding data to our view';
 * $view->list = array('Some', 'example', 'data', 'for', 'you');
 * $view->render('insert-data');
 * 
 */