<?php

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

require dirname(__DIR__) . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createMutable(dirname(__DIR__));
$dotenv->load();

$container = new Container();
$app       = new Slim\App($container);

/**
 * Merge the default container configuratin settings with the
 * config variables defined in the 'config/app.php' file.
 */
$container = $app->getContainer();
$settings  = $container->get("settings");
$configs   = require dirname(__DIR__) . '/config/app.php';
$settings->replace(array_merge($settings->all(), $configs));

$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    return $response
        ->getBody()
        ->write(config('app_name'));
});

$app->run();
