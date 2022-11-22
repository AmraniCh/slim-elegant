<?php


if (!function_exists('_env')) {
    /**
     * Get environnement variables.
     * 
     * @throws \LogicException
     * @return mixed
     */
    function _env(string $key)
    {
        if (!array_key_exists($key, $_ENV)) {
            throw new \LogicException("'$key' environnement variable not found.");
        }

        $value = $_ENV[$key];

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

if (!function_exists('config')) {
    /**
     * Get configuration variables.
     *
     * @throws \LogicException
     * @return mixed
     */
    function config(string $key)
    {
        global $app;

        $configs = $app->getAppConfiguration();

        if (!array_key_exists($key, $configs)) {
            throw new \LogicException("'$key' not found in configuration variables file.");
        }

        return $configs[$key];
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