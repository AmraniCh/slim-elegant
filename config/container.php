<?php

use Jenssegers\Blade\Blade;

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
        return new \App\kernel\Whoops(config('app_debug'));
    },

    'phpErrorHandler' => function() {
        return new \App\kernel\Whoops(config('app_debug'));
    },

    'notFoundHandler' => function() {
        return function ($request, Slim\Http\Response $response) {
            return $response->withStatus(404)
                ->withHeader('Content-Type', 'text/html')
                ->write('<h1>Page not found.</h1>');
        };
    },

    'view' => function (): Blade {
        return new Blade(config('views_path'), config('blade_cache_path'));
    },

];


