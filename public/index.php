<?php

use Slim\Http\Request;
use Slim\Http\Response;

require dirname(__DIR__) . '/vendor/autoload.php';

$app = new Slim\App();

$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    return $response
        ->getBody()
        ->write("Hello " . $args['name']);
});

$app->run();