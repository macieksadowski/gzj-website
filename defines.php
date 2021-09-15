<?php

define('BASE_FOLDER', basename(pathinfo($_SERVER['SCRIPT_FILENAME'])['dirname']));
define('SITE_ROOT', realpath(dirname(__FILE__)));
define('SITE_URL', 'http://'.$_SERVER['HTTP_HOST'].'/'.BASE_FOLDER);