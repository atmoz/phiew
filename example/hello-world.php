<?php

// The autoloader includes what we need
require_once 'autoload.php';

// The simplest way to render a template
echo Phiew_View::render('views/hello-world');
