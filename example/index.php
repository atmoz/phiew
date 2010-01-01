<?php

require_once '../library/Phiew/View.php';

$viewFolder = dirname(__FILE__) . '/views';
$view = new \Phiew\View($viewFolder);

// Alternative method for setting data: $view->list = array(...);
$list = array('Some', 'example', 'data', 'for', 'you');
$view->render('index', array('list' => $list));
