<?php

use Slim\Http\Request;
use Slim\Http\Response;

require dirname(__DIR__) . '/vendor/autoload.php';

// initialize the slim application
$container = new Slim\Container();
$app       = new App\Kernal\Application\App(dirname(__DIR__), $container);

$app->loadEnvironnement();
$app->initConfiguration();

$app->get('/', function (Request $request, Response $response, array $args) {
    return $response
        ->getBody()
        ->write(config('app_name'));
});

// run the application
$app->run();
