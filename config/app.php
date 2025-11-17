<?php

return [

    /**
     * Application name.
     * 
     * It very helpful to have a global variable that defines the application name
     * and require it whenever you need it in your application. 
     */
    'app_name' => _env('APP_NAME'),

    /**
     * Debug mode.
     * 
     * When disabled the application doesn't show any details about exception
     * and just shows a friendly message to the user.
     * 
     * Note: This option must disabled in production.
     */
    'app_debug' => _env('APP_DEBUG'),

    /**
     * Database settings.
     */
    'database'   => [
        'driver'     => _env('DB_DRIVER'),
        'host'       => _env('DB_HOST'),
        'username'   => _env('DB_USERNAME'),
        'password'   => _env('DB_PASSWORD'),
        'database'   => _env('DB_DATABASE'),
        'collection' => _env('DB_COLLECTION'),
        'prefix'     => _env('DB_PREFIX'),
    ],

    /**
     * Specify where views templates are located.
     */
    'views_path' => $app->getBasePath() . '/resources/views',

    /**
     * The base cache path. 
     */
    'cache_path' => $app->getBasePath() . '/cache',

    /**
     * Blade cache path.
     */
    'blade_cache_path' => $app->getBasePath() . '/cache/blade',

    /**
     * //////////////////////////////
     * Slim application configuration
     * //////////////////////////////
     * 
     * Here you can override and change the Slim default settings values.
     * 
     * @link https://www.slimframework.com/docs/v3/objects/application.html#application-configuration
     */

     /**
      * Tells the slim whether to show exception details or not.
      *
      * Default: false
      */
    'displayErrorDetails' => _env('APP_DEBUG') === true,

];