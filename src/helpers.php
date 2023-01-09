<?php


if (!function_exists('_env')) {
    /**
     * @return mixed
     * 
     * @throws \LogicException
     */
    function _env(string $key)
    {
        if (!array_key_exists($key, $_ENV)) {
            throw new \LogicException("'$key' does not exist with environment variables.");
        }

        return $_ENV[$key];
    }
}

if (!function_exists('config')) {
    /**
     * Get configuration variables.
     * 
     * @return mixed
     * 
     * @throws \LogicException
     */
    function config(string $key)
    {
        global $app;

        $configs = require $app->getAppConfigFile();

        if (!array_key_exists($key, $configs)) {
            throw new \LogicException("'$key' configuration variable not exist.");
        }

        $value = $configs[$key];

        if ($value instanceof \Closure) {
            return call_user_func($value);
        }

        if ($value === 'true') {
            return true;
        }
        
        if ($value === 'false') {
            return false;
        }

        return $value;
    }
}

if (!function_exists('route')) {

    function route(string $routeName, array $data = []): string
    {
        global $app;
        
        return $app
            ->getContainer()
            ->get('router')
            ->pathFor($routeName, $data);
    }
}