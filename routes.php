<?php

use Slim\Http\Request;
use Slim\Http\Response;

$app->get('/', function (Request $request, Response $response, array $args) use ($app) {
    return $response
        ->getBody()
        ->write($app->getContainer()->get('generateQuoteService'));
});