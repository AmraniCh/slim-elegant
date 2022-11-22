<?php

use App\Controllers\HomeController;
use App\Kernel\Http\RedirectResponse;
use Slim\Http\Request;
use Slim\Http\Response;

$verifyCsrfMiddleware = function ($request, $response, $next) use ($app) {
    if ($request->getAttribute('csrf_status') === false) {
        return new App\Kernel\Http\PlainResponse('CSRF check fails', 400);
    }

    return $next($request, $response);
};

$app->group('', function () use ($app, $verifyCsrfMiddleware) {
    $app->get('/', HomeController::class . ':index');

    $app->post('/login', function (Request $request, Response $response) use ($app) {
        return $response->getBody()->write("Request is safe!");
    })
        ->setName('login')
        ->add($verifyCsrfMiddleware);
        
})->add($app->getContainer()->get('csrf'));
