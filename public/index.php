<?php

require dirname(__DIR__) . '/vendor/autoload.php';

// the app constructor support a second parameter for the application container 
// if the parameter is omitted the container will be configured using the container.php file.
$app = new App\Kernel\Application\App(dirname(__DIR__));

$app
    ->loadEnvironment()
    ->loadConfiguration()
    ->loadEloquent()
    ->loadRoutes()
    ->loadMiddlewares();

// run the application
$app->run();
