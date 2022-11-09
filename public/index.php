<?php

use Slim\Http\Request;
use Slim\Http\Response;

require dirname(__DIR__) . '/vendor/autoload.php';

// load environnement variables from the .env file
$dotenv = Dotenv\Dotenv::createMutable(dirname(__DIR__));
$dotenv->load();

// initialize the slim application
$container = new Slim\Container();
$app       = new Slim\App($container);

// merge the default container configuratin settings with 
// configuration variables defined in the 'config/app.php' file.
$settings  = $container->get("settings");
$configs   = require dirname(__DIR__) . '/config/app.php';
$settings->replace(array_merge($settings->all(), $configs));

$app->get('/', function (Request $request, Response $response, array $args) {
    return $response
        ->getBody()
        ->write(config('app_name'));
});

// run the application
$app->run();
