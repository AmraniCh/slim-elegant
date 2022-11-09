<?php

if (!function_exists('config')) {

    function config($key)
    {
        global $app;

        $container = $app->getContainer();

        if ($container->get('settings')->has($key)) {
            $value = $container->get('settings')->get($key);
        } else {
            $path = dirname(__DIR__) . '/config/app.php';

            if (!file_exists($path)) {
                throw new \RuntimeException("'config/app.php' configuration file not exists.");
            }

            $config = include($path);

            if (!array_key_exists($key, $config)) {
                throw new \LogicException("'$key' not found in configuration variables file.");
            }

            $value = $config[$key];
        }

        if ($value instanceof \Closure) {
            return call_user_func($value);
        }

        if ($value === 'true') {
            return true;
        } elseif ($value === 'false') {
            return false;
        }

        return $value;
    }
}
