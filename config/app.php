<?php

return [

    /**
     * The application name.
     * 
     * It very helpul to have a global variable that defines the application name
     * and require it whenver you need it in your application. 
     */
    'app_name' => $_ENV['APP_NAME'],

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