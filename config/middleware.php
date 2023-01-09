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

    /**
     * Session Middleware.
     * @link https://github.com/bryanjhv/slim-session
     */
    new Slim\Middleware\Session(require $app->getBasePath() . '/config/session.php'),

];
