<?php

require dirname(__DIR__) . '/vendor/autoload.php';

// the App\Kernel\App class support a second parameter
// for the application container, if the parameter is
// not specified the container will be configured using 
// the container.php file, you can also pass a string 
// indicating a special container file location.
$app = new App\Kernel\App(dirname(__DIR__));

// do the magic
$app
    ->loadEnvironnement()
    ->loadConfiguration()
    ->loadEloquent()
    ->loadRoutes()
    ->loadMiddlewares();

// run the application
$app->run();
