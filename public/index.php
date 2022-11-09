<?php

use Dotenv\Dotenv;
use Slim\Http\Request;
use Slim\Http\Response;

require dirname(__DIR__) . '/vendor/autoload.php';

$app = new Slim\App([
    'settings' => [
        'displayErrorDetails' => true
    ]
]);

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$settings = $app->getContainer()->get("settings");
$configs  = require dirname(__DIR__) . '/config/app.php';
$settings->replace(array_merge($settings->all(), $configs));

$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    return $response
        ->getBody()
        ->write(config('app_name'));
});

$app->run();
