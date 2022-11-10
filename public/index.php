<?php

use Slim\Http\Request;
use Slim\Http\Response;

require dirname(__DIR__) . '/vendor/autoload.php';

$app = new App\Kernal\Application\App(dirname(__DIR__));

$app->loadEnvironnement();
$app->initConfiguration();

$app->get('/', function (Request $request, Response $response, array $args) use ($app) {
    return $response
        ->getBody()
        ->write($app->getContainer()->get('generateQuoteService'));
});

// run the application
$app->run();
