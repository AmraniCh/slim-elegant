<?php

require dirname(__DIR__) . '/vendor/autoload.php';

$app = new App\Kernal\App(dirname(__DIR__));

$app->loadEnvironnement();
$app->loadConfiguration();
$app->loadRoutes();
$app->loadMiddlewares();

$app->run();
