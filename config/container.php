<?php

return [

    'generateQuoteService' => function() {
        return "This world shall know pain!";
    },

    /**
     * ************************
     * Slim container services.
     * ************************
     */
    'errorHandler' => function() {
        return new \App\Kernal\Whoops(config('app_debug'));
    },

    'phpErrorHandler' => function() {
        return new \App\Kernal\Whoops(config('app_debug'));
    },

    // define a custom not found handler
    'notFoundHandler' => function() {
        return function ($request, Slim\Http\Response $response) {
            return $response->withStatus(404)
                ->withHeader('Content-Type', 'text/html')
                ->write('<h1>Page not found.</h1>');
        };
    },

];


