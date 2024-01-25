<?php

require_once '/var/www/html/vendor/autoload.php'; // Composer autoload

use Api\Application;

$app = new Application();
$app->run();