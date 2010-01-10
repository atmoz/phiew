<?php

require_once '../library/Phiew/View.php';

$view = new Phiew_View(dirname(__FILE__) . '/views');
$view->render('helpers');