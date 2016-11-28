<?php
//autoload composer things
require __DIR__ . '/../vendor/autoload.php';
// print_r(get_declared_classes());

use \Hagane\Env\Dotenv;

$dotenv = new Dotenv(__DIR__.'/..//');
$dotenv->load();

$config = require __DIR__ . '/../config/app.php';

$app = \Hagane\App::getInstance();
$app->start($config);

return $app;
