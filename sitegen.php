<?php

require 'vendor/autoload.php';

define("__APPDIR__", __DIR__);
define("__RESDIR__", __APPDIR__ . '/res');
define("__CONFDIR__", __RESDIR__ . '/config');
define("__TMPLDIR__", __RESDIR__ . '/template');

use Sitegen\Console\Application;

$application = new Application;

$application->run();
