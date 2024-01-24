<?php

$vendorDir = '/var/www/html/vendor/autoload.php';
require $vendorDir; // Composer autoload

use Api\Application;
$app = new Application();
$app->run();