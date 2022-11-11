<?php

return [

    /**
     * Define your middlewares here.
     * 
     * These middlewares will be executed for every incoming HTTP request.
     * 
     * If you want to add middleware to a specific request(s), you can do 
     * so when you define a route or a route group.
     * 
     * Note: The last defined middleware will be executed first. 
     */

    function ($request, $response, $next) {
        $response->getBody()->write('BEFORE-');
        $response = $next($request, $response);
        $response->getBody()->write('-AFTER');
        return $response;
    },

];
