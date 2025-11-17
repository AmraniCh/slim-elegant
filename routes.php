<?php

use Slim\Http\Request;
use Slim\Http\Response;
use App\Controllers\HomeController;
use App\Kernel\Middleware\VerifyCrsf;

$app->group('', function () use ($app) {

    $app
        ->get('/', HomeController::class . ':index')
        ->add($app->getContainer()->get('csrf'));

    $app
        ->post('/login', function (Request $request, Response $response) use ($app) {
            return $response->getBody()->write("request is from safe origin!");
         })
        ->setName('login')
        ->add(new VerifyCrsf);
        
});
