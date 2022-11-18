<?php


if (!function_exists('_env')) {
    /**
     * Get environnement variables.
     *
     * @param string $key
     * @throws \LogicException
     * @return mixed
     */
    function _env($key)
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
     * Get configaration variables.
     *
     * @param string $key
     * @throws \LogicException|\RuntimeException
     * @return mixed
     */
    function config($key)
    {
        global $app;

        $path = dirname(__DIR__) . '/config/app.php';

        if (!file_exists($path)) {
            throw new \RuntimeException("'config/app.php' configuration file not exists.");
        }

        $config = include($path);

        if (!array_key_exists($key, $config)) {
            throw new \LogicException("'$key' not found in configuration variables file.");
        }

        return $config[$key];
    }
}

if (!function_exists('route')) {

    function route($routeName, $data = [])
    {
        global $app;
        
        return $app
            ->getContainer()
            ->get('router')
            ->pathFor($routeName, $data);
    }
}