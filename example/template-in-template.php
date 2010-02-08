<?php

require_once '../library/Phiew/View/Template.php';
require_once '../library/Phiew/View.php';

define('PHIEW_VIEW_DIR', dirname(__FILE__) . '/views');

Phiew_View::render('template-in-template');