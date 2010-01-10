<?php

// We need that class first
require_once '../library/Phiew/View.php';

// Then we create a view pointed at our views folder, so it knows where to look ...
$view = new Phiew_View(dirname(__FILE__) . '/views');

// ... when we try to render "hello-world"
$view->render('hello-world');