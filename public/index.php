<?php

require dirname(__DIR__) . '/vendor/autoload.php';

// the kernel app class support a second parameter for the application container, if the parameter is not 
// specified the container will be configured using the container.php file, you can also pass a string 
// indicating a special container file location.
$app = new App\Kernel\Application\App(dirname(__DIR__));

// do the magic
$app
    ->loadEnvironment()
    ->loadConfiguration()
    ->loadEloquent()
    ->loadRoutes()
    ->loadMiddlewares();

// run the application
$app->run();
