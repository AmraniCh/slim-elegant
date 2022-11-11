<?php

return [

    /**
     * Application name.
     * 
     * It very helpul to have a global variable that defines the application name
     * and require it whenver you need it in your application. 
     */
    'app_name' => env('APP_NAME'),

    /**
     * Debug mode.
     * 
     * When disabled the application deson't show any details about exception
     * and just shows a friendly message to the user.
     * 
     * Note: This option must disabled in production.
     */
    'app_debug' => env('APP_DEBUG'),

    /**
     * Databse settings.
     */
    'database'   => [
        'driver'    => env('DB_DRIVER'),
        'host'      => env('DB_HOST'),
        'username'  => env('DB_USERNAME'),
        'password'  => env('DB_PASSWORD'),
        'database'  => env('DB_DATABSE'),
        'collation' => env('DB_collation'),
        'prefix'    => env('DB_PREFIX'),
    ],

    /**
     * //////////////////////////////
     * Slim application configuration
     * //////////////////////////////
     * 
     * @link https://www.slimframework.com/docs/v3/objects/application.html#application-configuration
     */

     /**
      * Tells the slim Whether to show exception details or not.
      *
      * Default: false
      */
    'displayErrorDetails' => env('APP_DEBUG') === true,

];