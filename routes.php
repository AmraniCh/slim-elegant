<?php

use App\Controllers\HomeController;
use Slim\Http\Request;
use Slim\Http\Response;

$app->get('/', HomeController::class . ':index');

$app->get('/quote', function (Request $request, Response $response, array $args) use ($app) {
    return $response
        ->getBody()
        ->write($app->getContainer()->get('generateQuoteService'));
});