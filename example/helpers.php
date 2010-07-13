<?php

require_once 'autoload.php';

date_default_timezone_set('UTC'); // Just to make date() shut up on fresh installs

echo Phiew_View::render('views/helpers');
