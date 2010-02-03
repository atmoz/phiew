<?php

require_once '../library/Phiew/View/Template.php';
require_once '../library/Phiew/View.php';

define('PHIEW_VIEW_DIR', dirname(__FILE__) . '/views');

Phiew_View::render('insert-data', array(
	'title' => 'Adding data to our view',
	'list'  => array('Some', 'example', 'data', 'for', 'you')
));


/* Alternative method for setting data, using template object:
 * 
 * $template = new Phiew_View_Template();
 * $template->title = 'Adding data to our view';
 * $template->list  = array('Some', 'example', 'data', 'for', 'you');
 * $template->render('insert-data');
 * 
 */