<?php

require_once '../library/Phiew/View.php';

$viewFolder = dirname(__FILE__) . '/views';
$view = new Phiew_View($viewFolder);

$list = array('Some', 'example', 'data', 'for', 'you');
// Alternative method for setting data: $view->list = array(...);
$view->render('index', array('list' => $list));

$view->clearData();
$view->render('useFoldersToo/dateSelect');