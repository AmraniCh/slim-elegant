<?php

require dirname(__DIR__) . '/vendor/autoload.php';

// the App\Kernal\App class support a second parameter
// for the application container, if the parameter is
// not specified the container will be configured using 
// the container.php file, you can also pass a string 
// indicating a special container file location.
$app = new App\Kernal\App(dirname(__DIR__));

// loaders
foreach([
    'loadEnvironnement', 
    'loadConfiguration', 
    'loadRoutes', 
    'loadMiddlewares'
    ] as $method) {
    call_user_func([$app, $method]);
}

// dd($app->getContainer()->get('errorHandler'));

// run the application
$app->run();