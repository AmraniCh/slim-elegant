<?php

return [

    /**
     * Register your middlewares here.
     *
     * These middlewares will be executed for every HTTP request and response.
     *
     * If you want to add middleware to a specific request(s), you can do
     * so when you define a route or a route group.
     *
     * Note: The last defined middleware will be executed first.
     */

    new Slim\Middleware\Session(config('session')),

];
